<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
    }

    /**
     * Get pagination template with multiple fallbacks
     * Untuk kompatibilitas lokal dan hosting
     */
    protected function getPaginationTemplate()
    {
        // Priority 1: Cek Pager/custom_pagination.php (huruf besar)
        $customPath1 = APPPATH . 'Views/Pager/custom_pagination.php';
        if (file_exists($customPath1)) {
            return 'custom_pagination';
        }

        // Priority 2: Cek pager/custom_pagination.php (huruf kecil)
        $customPath2 = APPPATH . 'Views/pager/custom_pagination.php';
        if (file_exists($customPath2)) {
            return 'custom_pagination_backup';
        }

        // Fallback ke default jika custom tidak ada
        return 'default_full';
    }

    /**
     * Render pagination dengan template yang aman
     */
    protected function renderPagination($pager)
    {
        try {
            return $pager->links('default', $this->getPaginationTemplate());
        } catch (\Exception) {
            // Jika error, gunakan template default
            return $pager->links('default', 'default_full');
        }
    }
}
