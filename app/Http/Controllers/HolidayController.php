<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HolidayController extends Controller
{
    // Display holidays list
    public function index()
    {
        $year = request('year', date('Y'));
        $type = request('type');
        
        $query = Holiday::whereYear('date', $year)
            ->orderBy('date');
            
        if ($type && $type !== 'all') {
            $query->where('type', $type);
        }
        
        $holidays = $query->paginate(20);
        
        $holidayStats = $this->getHolidayStats($year);
        
        return view('admin.holidays.index', compact(
            'holidays', 
            'holidayStats', 
            'year', 
            'type'
        ));
    }
    
    // Create holiday
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:public,company,optional',
            'description' => 'nullable|string|max:1000',
            'repeats_annually' => 'boolean',
        ]);
        
        // Check if holiday already exists
        $existing = Holiday::whereDate('date', $request->date)
            ->where('title', $request->title)
            ->exists();
            
        if ($existing) {
            return back()->with('error', 'A holiday with this title and date already exists.');
        }
        
        Holiday::create([
            'title' => $request->title,
            'date' => $request->date,
            'type' => $request->type,
            'description' => $request->description,
            'repeats_annually' => $request->repeats_annually ?? false,
        ]);
        
        return back()->with('success', 'Holiday added successfully.');
    }
    
    // Update holiday
    public function update(Request $request, $id)
    {
        $holiday = Holiday::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:public,company,optional',
            'description' => 'nullable|string|max:1000',
            'repeats_annually' => 'boolean',
        ]);
        
        $holiday->update($request->all());
        
        return back()->with('success', 'Holiday updated successfully.');
    }
    
    // Delete holiday
    public function destroy($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();
        
        return back()->with('success', 'Holiday deleted successfully.');
    }
    
    // Import holidays from CSV
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);
        
        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        
        $imported = 0;
        $skipped = 0;
        
        if (($handle = fopen($path, 'r')) !== false) {
            // Skip header row
            fgetcsv($handle);
            
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($data) < 3) {
                    continue;
                }
                
                $title = trim($data[0]);
                $date = trim($data[1]);
                $type = trim($data[2]);
                $description = isset($data[3]) ? trim($data[3]) : null;
                
                // Validate date
                if (!strtotime($date)) {
                    $skipped++;
                    continue;
                }
                
                // Check if holiday already exists
                $exists = Holiday::whereDate('date', $date)
                    ->where('title', $title)
                    ->exists();
                    
                if ($exists) {
                    $skipped++;
                    continue;
                }
                
                Holiday::create([
                    'title' => $title,
                    'date' => $date,
                    'type' => in_array($type, ['public', 'company', 'optional']) ? $type : 'public',
                    'description' => $description,
                    'repeats_annually' => true,
                ]);
                
                $imported++;
            }
            
            fclose($handle);
        }
        
        return back()->with('success', 
            "Imported {$imported} holidays. {$skipped} records skipped."
        );
    }
    
    // Export holidays to CSV
    public function export()
    {
        $year = request('year', date('Y'));
        
        $holidays = Holiday::whereYear('date', $year)
            ->orderBy('date')
            ->get();
        
        $filename = 'holidays_' . $year . '_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($holidays) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, ['Title', 'Date', 'Type', 'Description', 'Repeats Annually']);
            
            // Data
            foreach ($holidays as $holiday) {
                fputcsv($file, [
                    $holiday->title,
                    $holiday->date->format('Y-m-d'),
                    $holiday->type,
                    $holiday->description ?? '',
                    $holiday->repeats_annually ? 'Yes' : 'No',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    // Get holiday statistics
    private function getHolidayStats($year)
    {
        $holidays = Holiday::whereYear('date', $year)->get();
        
        return [
            'total' => $holidays->count(),
            'public' => $holidays->where('type', 'public')->count(),
            'company' => $holidays->where('type', 'company')->count(),
            'optional' => $holidays->where('type', 'optional')->count(),
            'repeating' => $holidays->where('repeats_annually', true)->count(),
            'upcoming' => $holidays->where('date', '>=', today())->count(),
        ];
    }
    
    // Get holidays for calendar (API endpoint)
    public function calendar(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        
        $holidays = Holiday::whereBetween('date', [$start, $end])->get();
        
        $events = [];
        foreach ($holidays as $holiday) {
            $events[] = [
                'id' => $holiday->id,
                'title' => $holiday->title,
                'start' => $holiday->date->format('Y-m-d'),
                'end' => $holiday->date->format('Y-m-d'),
                'color' => $this->getHolidayColor($holiday->type),
                'extendedProps' => [
                    'type' => $holiday->type,
                    'description' => $holiday->description,
                    'repeats' => $holiday->repeats_annually,
                ]
            ];
        }
        
        return response()->json($events);
    }
    
    // Get color for holiday type
    private function getHolidayColor($type)
    {
        return match($type) {
            'public' => '#dc3545',    // Red
            'company' => '#17a2b8',   // Cyan
            'optional' => '#6c757d',  // Gray
            default => '#6c757d',
        };
    }
}