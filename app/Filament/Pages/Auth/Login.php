<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as FilamentLogin;
use Filament\Forms\Components\TextInput;

class Login extends FilamentLogin
{
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    protected function getEmailFormComponent(): TextInput
    {
        return TextInput::make('username')
            ->label('Username')
            ->required()
            ->autofocus()
            ->autocomplete('username')
            ->maxLength(255)
            ->extraInputAttributes([
                'tabindex' => 1,
            ]);
    }
}
