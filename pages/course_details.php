<?php
// Backwards-compatible fallback for singular file name used in some links
// This file simply includes the canonical `courses_details.php` while preserving the query string.

// Ensure correct path to shared includes if the included file expects __DIR__ to be pages/
chdir(__DIR__);

// Forward to the canonical file
require_once __DIR__ . '/courses_details.php';
