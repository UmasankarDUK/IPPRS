<input type="number"
       name="{{ $name }}"
       value="{{ old($name, $val ?? 0) }}"
       min="0"
       class="w-full text-center rounded-md border px-1 py-1 text-xs font-mono font-bold focus:outline-none focus:ring-1 transition"
       style="border-color:#E2EDE9;color:{{ $color ?? '#374151' }};background:transparent;min-width:52px;max-width:70px;
              focus-ring-color:#006B4F;"
       onfocus="this.select()">
