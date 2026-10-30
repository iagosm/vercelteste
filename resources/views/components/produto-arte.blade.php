<div {{ $attributes->merge(['class' => 'w-full leading-[0]']) }}>
    @switch($forma)
        @case('laptop')
            <svg viewBox="0 0 400 480" class="block h-auto w-full" role="img" aria-label="Ilustração de notebook">
                <ellipse cx="200" cy="338" rx="170" ry="9" fill="#000" opacity=".28"/>
                <rect x="64" y="122" width="272" height="178" rx="12" fill="{{ $escura }}"/>
                <rect x="64" y="122" width="272" height="178" rx="12" fill="none" stroke="{{ $clara }}" stroke-width="2" opacity=".5"/>
                <rect x="72" y="130" width="256" height="162" rx="6" fill="#0A0F0C"/>
                <circle cx="200" cy="212" r="44" fill="none" stroke="{{ $destaque }}" stroke-width="2" opacity=".7"/>
                <circle cx="200" cy="212" r="20" fill="{{ $destaque }}"/>
                <path d="M34 300 H366 L354 322 Q352 328 346 328 H54 Q48 328 46 322 Z" fill="{{ $cor }}"/>
                <rect x="164" y="300" width="72" height="7" rx="3.5" fill="{{ $escura }}"/>
            </svg>
            @break
        @case('tablet')
            <svg viewBox="0 0 400 480" class="block h-auto w-full" role="img" aria-label="Ilustração de tablet">
                <ellipse cx="200" cy="432" rx="120" ry="9" fill="#000" opacity=".28"/>
                <rect x="84" y="52" width="232" height="350" rx="28" fill="{{ $cor }}"/>
                <rect x="84" y="52" width="232" height="350" rx="28" fill="none" stroke="{{ $clara }}" stroke-width="2" opacity=".55"/>
                <rect x="97" y="65" width="206" height="324" rx="17" fill="#0A0F0C"/>
                <circle cx="200" cy="218" r="48" fill="none" stroke="{{ $destaque }}" stroke-width="2" opacity=".7"/>
                <circle cx="200" cy="218" r="22" fill="{{ $destaque }}"/>
                <rect x="150" y="348" width="100" height="5" rx="2.5" fill="#26322A"/>
            </svg>
            @break
        @case('watch')
            <svg viewBox="0 0 400 480" class="block h-auto w-full" role="img" aria-label="Ilustração de relógio">
                <rect x="160" y="22" width="80" height="150" rx="20" fill="{{ $escura }}"/>
                <rect x="160" y="288" width="80" height="150" rx="20" fill="{{ $escura }}"/>
                <rect x="132" y="150" width="136" height="160" rx="42" fill="{{ $cor }}"/>
                <rect x="132" y="150" width="136" height="160" rx="42" fill="none" stroke="{{ $clara }}" stroke-width="2" opacity=".55"/>
                <rect x="144" y="162" width="112" height="136" rx="31" fill="#0A0F0C"/>
                <rect x="268" y="204" width="9" height="36" rx="4.5" fill="{{ $clara }}"/>
                <circle cx="200" cy="230" r="38" fill="none" stroke="#26322A" stroke-width="8"/>
                <path d="M200 192 A38 38 0 1 1 163 240" fill="none" stroke="{{ $destaque }}" stroke-width="8" stroke-linecap="round"/>
            </svg>
            @break
        @case('airpods')
            <svg viewBox="0 0 400 480" class="block h-auto w-full" role="img" aria-label="Ilustração de fones de ouvido sem fio e estojo">
                <ellipse cx="200" cy="372" rx="110" ry="9" fill="#000" opacity=".28"/>
                <rect x="124" y="228" width="152" height="136" rx="54" fill="{{ $cor }}"/>
                <path d="M124 282 H276" stroke="{{ $escura }}" stroke-width="3"/>
                <circle cx="200" cy="322" r="4.5" fill="{{ $destaque }}"/>
                <ellipse cx="146" cy="124" rx="32" ry="38" fill="{{ $cor }}" stroke="{{ $escura }}" stroke-width="2"/>
                <rect x="140" y="140" width="22" height="78" rx="11" fill="{{ $cor }}" stroke="{{ $escura }}" stroke-width="2"/>
                <ellipse cx="254" cy="124" rx="32" ry="38" fill="{{ $cor }}" stroke="{{ $escura }}" stroke-width="2"/>
                <rect x="238" y="140" width="22" height="78" rx="11" fill="{{ $cor }}" stroke="{{ $escura }}" stroke-width="2"/>
            </svg>
            @break
        @case('headphones')
            <svg viewBox="0 0 400 480" class="block h-auto w-full" role="img" aria-label="Ilustração de headphone">
                <ellipse cx="200" cy="396" rx="120" ry="9" fill="#000" opacity=".28"/>
                <path d="M116 250 V196 A84 84 0 0 1 284 196 V250" fill="none" stroke="{{ $escura }}" stroke-width="20" stroke-linecap="round"/>
                <rect x="82" y="226" width="58" height="128" rx="29" fill="{{ $cor }}"/>
                <rect x="260" y="226" width="58" height="128" rx="29" fill="{{ $cor }}"/>
                <rect x="132" y="240" width="14" height="100" rx="7" fill="{{ $escura }}"/>
                <rect x="254" y="240" width="14" height="100" rx="7" fill="{{ $escura }}"/>
            </svg>
            @break
        @case('puck')
            <svg viewBox="0 0 400 480" class="block h-auto w-full" role="img" aria-label="Ilustração de carregador sem fio">
                <ellipse cx="200" cy="336" rx="110" ry="9" fill="#000" opacity=".28"/>
                <path d="M200 322 C200 392 140 392 120 440" fill="none" stroke="{{ $cor }}" stroke-width="9" stroke-linecap="round"/>
                <circle cx="200" cy="230" r="98" fill="{{ $cor }}"/>
                <circle cx="200" cy="230" r="72" fill="none" stroke="{{ $escura }}" stroke-width="3"/>
                <circle cx="200" cy="230" r="30" fill="{{ $escura }}" opacity=".55"/>
                <circle cx="200" cy="230" r="6" fill="{{ $destaque }}"/>
            </svg>
            @break
        @case('cable')
            <svg viewBox="0 0 400 480" class="block h-auto w-full" role="img" aria-label="Ilustração de cabo USB-C">
                <path d="M120 384 C60 290 340 330 280 160" fill="none" stroke="{{ $cor }}" stroke-width="12" stroke-linecap="round"/>
                <rect x="98" y="370" width="42" height="64" rx="10" fill="{{ $clara }}" transform="rotate(-24 119 402)"/>
                <rect x="260" y="98" width="42" height="64" rx="10" fill="{{ $clara }}" transform="rotate(8 281 130)"/>
                <circle cx="119" cy="414" r="4" fill="{{ $escura }}"/>
                <circle cx="281" cy="108" r="4" fill="{{ $escura }}"/>
            </svg>
            @break
        @case('tag')
            <svg viewBox="0 0 400 480" class="block h-auto w-full" role="img" aria-label="Ilustração de localizador de objetos">
                <ellipse cx="200" cy="336" rx="100" ry="9" fill="#000" opacity=".28"/>
                <circle cx="200" cy="230" r="98" fill="{{ $cor }}"/>
                <circle cx="200" cy="230" r="62" fill="{{ $escura }}" opacity=".7"/>
                <circle cx="200" cy="230" r="16" fill="{{ $cor }}"/>
                <circle cx="200" cy="230" r="98" fill="none" stroke="{{ $clara }}" stroke-width="2" opacity=".6"/>
            </svg>
            @break
        @default
            <svg viewBox="0 0 400 480" class="block h-auto w-full" role="img" aria-label="Ilustração de celular">
                <ellipse cx="200" cy="462" rx="92" ry="9" fill="#000" opacity=".28"/>
                <rect x="112" y="30" width="176" height="420" rx="36" fill="{{ $cor }}"/>
                <rect x="112" y="30" width="176" height="420" rx="36" fill="none" stroke="{{ $clara }}" stroke-width="2" opacity=".55"/>
                <rect x="286" y="130" width="5" height="58" rx="2.5" fill="{{ $escura }}"/>
                <rect x="109" y="104" width="5" height="34" rx="2.5" fill="{{ $escura }}"/>
                <rect x="109" y="150" width="5" height="48" rx="2.5" fill="{{ $escura }}"/>
                <rect x="128" y="46" width="88" height="88" rx="24" fill="{{ $escura }}"/>
                <circle cx="155" cy="73" r="15" fill="#0A0F0C" stroke="{{ $clara }}" stroke-width="3"/>
                <circle cx="155" cy="107" r="15" fill="#0A0F0C" stroke="{{ $clara }}" stroke-width="3"/>
                <circle cx="190" cy="90" r="15" fill="#0A0F0C" stroke="{{ $clara }}" stroke-width="3"/>
                <circle cx="236" cy="62" r="6" fill="{{ $clara }}" opacity=".8"/>
            </svg>
    @endswitch
</div>
