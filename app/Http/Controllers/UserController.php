<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{

    /**
     * Gera a página de login
     *
     * @return View|RedirectResponse
     */
    #[Route('/login', name: 'login', methods: 'get')]
    public function get(): View|RedirectResponse
    {
        if (Auth::check()) {
            return to_route(Auth::user()->type);
        } else {
            return view('login');
        }
    }

    /**
     * Realiza a autenticação do usuário
     *
     * @param Request $request
     * @return RedirectResponse
     */
    #[Route('/login', name: 'auth', methods: 'post')]
    public function post(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($dados, $request->input('remember'))) {
            return to_route(Auth::user()->type);
        } else {
            return redirect()->back()->withErrors([
                'password' => ['E-mail e/ou senha inválidos!']
            ])->withInput();
        }
    }

    /**
     * Realiza o logout
     *
     * @returns RedirectResponse
     */
    #[Route('/logout', name: 'logout', methods: 'get')]
    public function logout(): RedirectResponse
    {
        Auth::logout();
        return to_route('login');
    }
}
