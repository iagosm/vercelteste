<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Support\DemoData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function index(): View
    {
        $produtos = DemoData::produtos();

        $categorias = DemoData::categorias()->map(fn (array $categoria): array => $categoria + [
            'total_produtos' => $produtos->where('categoria', $categoria['slug'])->count(),
        ]);

        return view('painel.categorias.index', ['categorias' => $categorias]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
        ]);

        return back()->with('status', "Categoria {$dados['nome']} adicionada. Nada foi salvo.");
    }

    /**
     * Liga ou desliga a exibição da categoria na loja.
     */
    public function update(Request $request, string $categoria): RedirectResponse
    {
        abort_if(DemoData::categoria($categoria) === null, 404);

        return back()->with('status', $request->boolean('ativa') ? 'Categoria exibida na loja. Nada foi salvo.' : 'Categoria ocultada da loja. Nada foi salvo.');
    }

    public function destroy(string $categoria): RedirectResponse
    {
        abort_if(DemoData::categoria($categoria) === null, 404);

        if (DemoData::produtos()->contains('categoria', $categoria)) {
            return back()->with('status', 'Mova ou remova os produtos antes.');
        }

        return back()->with('status', 'Categoria removida. Nada foi salvo.');
    }
}
