<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\PHPMultiVersion;

class PHPMultiVersionCheck extends BaseController
{
    protected PHPMultiVersion $phpConfig;

    public function __construct()
    {
        $this->phpConfig = new PHPMultiVersion();
    }

    public function index()
    {
        // Only allow in development environment
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('/')->with('error', 'Access denied.');
        }

        $report = $this->phpConfig->getCompatibilityReport();
        
        return view('php_multiversion_check', ['report' => $report]);
    }

    public function api()
    {
        // Only allow in development environment
        if (ENVIRONMENT !== 'development') {
            return $this->response->setJSON([
                'error' => 'Access denied'
            ])->setStatusCode(403);
        }

        $report = $this->phpConfig->getCompatibilityReport();
        
        return $this->response->setJSON($report);
    }

    public function requirements()
    {
        // Only allow in development environment
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('/')->with('error', 'Access denied.');
        }

        $extensions = $this->phpConfig->checkExtensions();
        $config = $this->phpConfig->getCurrentConfig();
        $version = $this->phpConfig->getVersionInfo();

        $data = [
            'extensions' => $extensions,
            'config' => $config,
            'version' => $version,
        ];

        return view('php_multiversion_requirements', $data);
    }
}
