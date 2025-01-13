<?php
namespace Commentics;

class MainInstall1Model extends Model
{
    public function getTimeZones()
    {
        $time_zones = \DateTimeZone::listIdentifiers();

        return $time_zones;
    }
}
