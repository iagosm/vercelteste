{{-- Visual de chave liga/desliga. Envolva num <button> ou <label> com role="switch". --}}
@props(['ligado' => false])

<span {{ $attributes->merge(['class' => 'relative inline-block h-[26px] w-11 flex-none rounded-full transition '.($ligado ? 'bg-ink' : 'bg-fog')]) }}>
    <i class="absolute top-[3px] size-5 rounded-full bg-white transition-all {{ $ligado ? 'left-[21px]' : 'left-[3px]' }}"></i>
</span>
