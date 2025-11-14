export default function blockZoom() {
  /************ Bloquer zoom clavier et roulette ************/
  window.addEventListener(
    "keydown",
    function (e) {
      const isCtrl = e.ctrlKey || e.metaKey;
      if (isCtrl && ["+", "-", "=", "0"].includes(e.key)) {
        e.preventDefault();
      }
      if (["ArrowLeft", "ArrowRight"].includes(e.key)) {
        e.preventDefault();
      }
    },
    { passive: false }
  );

  window.addEventListener(
    "wheel",
    function (e) {
      if (e.ctrlKey || e.metaKey) e.preventDefault();
    },
    { passive: false }
  );

  /************ Bloquer pinch, double-tap et gestures ************/
  let lastTouch = 0;

  window.addEventListener(
    "touchstart",
    function (e) {
      if (e.touches.length > 1) {
        e.preventDefault();
      }
    },
    { passive: false }
  );

  window.addEventListener(
    "touchend",
    function (e) {
      const now = Date.now();
      if (now - lastTouch <= 300) {
        e.preventDefault();
      }
      lastTouch = now;
    },
    { passive: false }
  );

  window.addEventListener(
    "gesturestart",
    function (e) {
      e.preventDefault();
    },
    { passive: false }
  );

  /************ Sécurité : corps ne dépasse pas viewport ************/
  function clampBodyWidth() {
    document.documentElement.style.overflowX = "hidden";
    document.body.style.overflowX = "hidden";
    document.body.style.maxWidth = "100vw";
  }

  clampBodyWidth();
  window.addEventListener("resize", clampBodyWidth);
}
