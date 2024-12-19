<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('sites.site_name', 'dashboard');
        $this->migrator->add('sites.site_description', 'Filament Admin Dashboard');
        $this->migrator->add('sites.site_keywords', 'Dashboard, Marketing, Programming');
        $this->migrator->add('sites.site_favicon', '');
        $this->migrator->add('sites.site_logo', '');
        $this->migrator->add('sites.site_author', 'Moataz Mosa');
        $this->migrator->add('sites.site_email', 'moatazsayed119@gmail.com');
        $this->migrator->add('sites.site_phone', '');
        $this->migrator->add('sites.site_social', []);
    }
};
