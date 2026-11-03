<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PermissionFilter implements FilterInterface
{
    private const MODULES = [
        'users' => 'utilisateurs', 'formations' => 'formations', 'ues' => 'ues',
        'ecs' => 'ecs', 'inscriptions-admin' => 'inscriptions_admin',
        'inscriptions-pedagogiques' => 'inscriptions_pedagogiques', 'emplois' => 'emplois',
        'notes' => 'notes', 'absences' => 'absences', 'paiements' => 'paiements',
        'profils' => 'profils',
    ];

    public function before(RequestInterface $request, $arguments = null)
    {
        $path = trim($request->getUri()->getPath(), '/');
        $segment = explode('/', $path)[0] ?? '';
        if (! isset(self::MODULES[$segment])) return null;
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Veuillez vous connecter.');
        }
        $action = $this->actionFor($request->getMethod(), $path);
        if (! hasPermission(self::MODULES[$segment], $action)) {
            return redirect()->to('/dashboard')->with('error', "Vous n'avez pas la permission d'effectuer cette action.");
        }

        // Les routes de gestion affichent toutes les données. Un étudiant qui
        // dispose uniquement du droit de lecture doit donc toujours être envoyé
        // vers son espace personnel, où les requêtes sont filtrées par son ID.
        if (session()->get('role') === 'etudiant') {
            $studentRoutes = [
                'notes'     => '/student/grades',
                'emplois'   => '/student/schedule',
                'paiements' => '/student/payments',
            ];

            if (isset($studentRoutes[$segment]) && $action === 'read') {
                return redirect()->to($studentRoutes[$segment]);
            }

            if (isset($studentRoutes[$segment])) {
                return redirect()->to('/student/dashboard')
                    ->with('error', 'Les étudiants peuvent uniquement consulter leurs propres informations.');
            }
        }
    }

    private function actionFor(string $method, string $path): string
    {
        if (preg_match('#/(edit|update)(/|$)#', $path)) return 'edit';
        if (preg_match('#/(delete|archive|restore)(/|$)#', $path)) return 'delete';
        if ($method !== 'GET' || preg_match('#/(new|bulk-add|bulk-select|bulk-store)(/|$)#', $path)) return 'add';
        return 'read';
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
