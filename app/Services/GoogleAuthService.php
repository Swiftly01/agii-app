<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;


class GoogleAuthService
{

  public const ALLOWED_USER_TYPES = ['customer', 'vendor', 'marketer', 'affiliate'];


  public function findOrCreateUser(SocialiteUser $googleUser, string $userType): User
  {
    $userType = $this->sanitizeUser($userType);

    if ($user = User::where('google_id', $googleUser->getId())->first()) {
      return $user;
    }

    if ($user = User::where('email', $googleUser->getEmail())->first()) {

      return $this->linkGoogleAccount($user, $googleUser);
    }

    return $this->createFromGoogle($googleUser, $userType); 
  }

  public function sanitizeUser(?string $userType): string
  {
    return in_array($userType, self::ALLOWED_USER_TYPES, true) ? $userType : 'customer';
  }


  private function linkGoogleAccount(User $user, SocialiteUser $googleUser): User
  {

    $user->forceFill([
      'google_id' => $googleUser->getId(),
      'avatar' => $user->avatar ?? $googleUser->getAvatar(),
      'email_verified_at' => $user->email_verified_at ?? now(),
    ])->save();

    return $user;
  }


  private function splitName(string $name): array
  {
    $parts = explode(' ', trim($name), 2);

    return [$parts[0], $parts[1] ?? ''];
  }

  private function createFromGoogle(SocialiteUser $googleUser, string $userType): User
  {

    [$firstName, $lastName] = $this->splitName($googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User');

    return User::create([
      'first_name' => $firstName,
      'last_name' => $lastName,
      'email' => $googleUser->getEmail(),
      'phone' => null,
      'password' => null,
      'google_id' => $googleUser->getId(),
      'avatar' => $googleUser->getAvatar(),
      'user_type' => $userType,
      'referral_code' => $this->generateReferralCode($firstName),
      'terms_accepted' => true,
      'newsletter_subscribed' => false,
      'email_verified_at' => now()
    ]);
  }

  private function generateReferralCode(string $firstName): string
  {

    $prefix =  strtoupper(Str::substr(preg_replace('/[^A-Za-z]/', '', $firstName) ?: 'USER', 0, 3));

    do {
      $code = $prefix . random_int(1000, 9999);
    } while (User::where('referral_code', $code)->exists());

    return $code;
  }
}
