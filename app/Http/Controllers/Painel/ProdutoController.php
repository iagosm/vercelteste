<?php

namespace App\Http\Controllers\Painel;

use App\Enums\Condicao;
use App\Enums\Disponibilidade;
use App\Http\Controllers\Controller;
use App\Support\DemoData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProdutoController extends Controller
{
    public function index(Request $request): View
    {
        $categoria = $request->string('categoria')->toString() ?: null;
        $busca = Str::of($request->string('q')->trim())->ascii()->lower()->toString();

        $produtos = DemoData::produtos()
            ->when($categoria, fn ($produtos) => $produtos->where('categoria', $categoria))
            ->when($busca !== '', fn ($produtos) => $produtos->filter(
                fn (array $p): bool => str_contains(Str::of($p['nome'])->ascii()->lower()->toString(), $busca)
            ))
            ->values();

        return view('painel.produtos.index', [
            'produtos' => $produtos,
            'categorias' => DemoData::categorias(),
            'categoriaAtual' => $categoria,
        ]);
    }

    public function create(): View
    {
        return view('painel.produtos.form', [
            'produto' => [
                'slug' => null,
                'nome' => '',
                'categoria' => 'iphone',
                'condicao' => Condicao::Novo,
                'descricao' => '',
                'preco' => '',
                'disponibilidade' => Disponibilidade::EmEstoque,
                'ativo' => true,
                'forma' => 'phone',
                'cores' => [['hex' => '#7C93C9']],
                'variacoes' => [['cor' => '', 'opcao' => '', 'adicional' => 0, 'estoque' => 1]],
            ],
            'categorias' => DemoData::categorias(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validar($request);

        return to_route('painel.produtos.index')->with('status', 'Produto cadastrado. Nada foi salvo.');
    }

    public function edit(string $produto): View
    {
        $produto = DemoData::produto($produto);

        abort_if($produto === null, 404);

        return view('painel.produtos.form', [
            'produto' => $produto,
            'categorias' => DemoData::categorias(),
        ]);
    }

    /**
     * Salva o formulário completo ou só liga/desliga a visibilidade (lista de produtos).
     */
    public function update(Request $request, string $produto): RedirectResponse
    {
        $produto = DemoData::produto($produto);

        abort_if($produto === null, 404);

        if ($request->has('somente_ativo')) {
            $ativo = $request->boolean('ativo');

            return back()->with('status', $ativo ? 'Produto visível na loja. Nada foi salvo.' : 'Produto ocultado da loja. Nada foi salvo.');
        }

        $this->validar($request);

        return to_route('painel.produtos.index')->with('status', "{$produto['nome']} atualizado. Nada foi salvo.");
    }

    public function destroy(string $produto): RedirectResponse
    {
        abort_if(DemoData::produto($produto) === null, 404);

        return to_route('painel.produtos.index')->with('status', 'Produto removido. Nada foi salvo.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validar(Request $request): array
    {
        return $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'categoria' => ['required', Rule::in(DemoData::categorias()->pluck('slug'))],
            'condicao' => ['required', Rule::enum(Condicao::class)],
            'descricao' => ['nullable', 'string'],
            'preco' => ['required', 'numeric', 'min:0'],
            'disponibilidade' => ['required', Rule::enum(Disponibilidade::class)],
            'ativo' => ['boolean'],
            'variacoes' => ['array'],
            'variacoes.*.cor' => ['nullable', 'string', 'max:100'],
            'variacoes.*.opcao' => ['nullable', 'string', 'max:100'],
            'variacoes.*.adicional' => ['nullable', 'numeric'],
            'variacoes.*.estoque' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
