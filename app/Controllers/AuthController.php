<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth/login');
    }

    public function attemptLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'user_id'    => $user['id'],
                'role'       => $user['role'],
                'profil_id'  => $user['profil_id'] ?? null,
                'nom'        => $user['nom'],
                'prenom'     => $user['prenom'],
                'isLoggedIn' => true,
            ]);
            return redirect()->to('/dashboard')->with('success', 'Bienvenue ' . $user['prenom']);
        } else {
            return redirect()->back()->with('error', 'Email ou mot de passe incorrect.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function forgotPassword()
    {
        return view('auth/forgot');
    }

    public function sendResetLink()
    {
        $email = $this->request->getPost('email');
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'Email non trouvé.');
        }

        // Générer un token et l'enregistrer en base (table à créer) ou utiliser un helper
        // Pour simplifier, on redirige avec un message
        return redirect()->to('/login')->with('success', 'Un lien de réinitialisation a été envoyé (simulation).');
    }

    public function resetPassword($token)
    {
        // Vérifier le token et afficher le formulaire
        return view('auth/reset', ['token' => $token]);
    }

    public function updatePassword()
    {
        // Mettre à jour le mot de passe
        return redirect()->to('/login')->with('success', 'Mot de passe mis à jour.');
    }
}
