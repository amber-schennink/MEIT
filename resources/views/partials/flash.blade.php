{{-- resources/views/partials/flash.blade.php --}}
<div
  id="flash-wrapper"
  class="fixed inset-x-0 top-24 sm:top-28 z-[9999] px-4 sm:px-6 pointer-events-none flex justify-center"
  aria-live="polite" aria-atomic="true"
>
  <div class="w-full max-w-lg sm:max-w-xl flex flex-col items-stretch gap-3">

    @if (session('msg'))
      <div
        class="pointer-events-auto w-full rounded-lg bg-main-light text-black py-6 px-8 flex items-center justify-between toast-enter"
        role="status" data-flash data-flash-autohide="8000"
      >
        <div class="pr-6">{{ session('msg') }}</div>
        <img src="{{ asset('assets/x.svg') }}" alt="Sluiten" title="Sluiten"
             class="w-7 h-7 cursor-pointer select-none mt-0.5"
             role="button" tabindex="0"
             onclick="window.closeToast(this.closest('[data-flash]'))"
             onkeydown="if(event.key==='Enter'||event.key===' '){this.click();}">
      </div>
    @endif

    @if (session('error'))
      <div
        class="pointer-events-auto w-full rounded-lg bg-red-300 text-red-900 py-6 px-8 flex items-center justify-between toast-enter"
        role="alert" data-flash data-flash-autohide="8000"
      >
        <div class="pr-6">{{ session('error') }}</div>
        <img src="{{ asset('assets/x.svg') }}" alt="Sluiten" title="Sluiten"
             class="w-7 h-7 cursor-pointer select-none mt-0.5"
             role="button" tabindex="0"
             onclick="window.closeToast(this.closest('[data-flash]'))"
             onkeydown="if(event.key==='Enter'||event.key===' '){this.click();}">
      </div>
    @endif

    @if ($errors->any())
      <div
        class="pointer-events-auto w-full rounded-lg bg-red-300 text-red-900 py-6 px-8 flex items-center justify-between toast-enter"
        role="alert" data-flash data-flash-autohide="8000"
      >
        <p class="font-semibold mb-2">Er ging iets mis:</p>
        <ul class="list-disc ml-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <div class="text-right mt-2">
          <img src="{{ asset('assets/x.svg') }}" alt="Sluiten" title="Sluiten"
               class="w-7 h-7 inline-block cursor-pointer align-middle select-none"
               role="button" tabindex="0"
               onclick="window.closeToast(this.closest('[data-flash]'))"
               onkeydown="if(event.key==='Enter'||event.key===' '){this.click();}">
        </div>
      </div>
    @endif

  </div>
</div>

<style>
@keyframes fadeSlideIn {
  from { opacity: 0; transform: translateY(-6px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeSlideOut {
  from { opacity: 1; transform: translateY(0); }
  to   { opacity: 0; transform: translateY(-6px); }
}
/* binnenkomen */
.toast-enter {
  animation: fadeSlideIn .25s ease-out both;
}
/* weggaan */
.toast-exit {
  animation: fadeSlideOut .25s ease-in both;
}
</style>

<script>
(function () {
  // Globale sluitfunctie met exit-animatie
  window.closeToast = function(el) {
    if (!el) return;
    el.classList.remove('toast-enter');
    el.classList.add('toast-exit');
    el.addEventListener('animationend', function handler() {
      el.removeEventListener('animationend', handler);
      if (el && el.parentNode) el.parentNode.removeChild(el);
    }, { once: true });
  };

  // Auto-hide (indien data-flash-autohide gezet)
  document.querySelectorAll('[data-flash-autohide]').forEach(function (el) {
    var ms = parseInt(el.getAttribute('data-flash-autohide'), 10);
    if (!isNaN(ms) && ms > 0) {
      setTimeout(function () {
        window.closeToast(el);
      }, ms);
    }
  });

  // ESC sluit de bovenste toast
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      var toasts = document.querySelectorAll('#flash-wrapper [data-flash]');
      if (toasts.length) {
        window.closeToast(toasts[toasts.length - 1]);
      }
    }
  });
})();
</script>
