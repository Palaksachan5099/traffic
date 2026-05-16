<?php

namespace App\Models;

/**
 * Domain alias for congestion / traffic reports (same Mongo collection as TrafficReport).
 */
class Congestion extends TrafficReport
{
    // Uses inherited $collection = 'traffic_reports'
}
