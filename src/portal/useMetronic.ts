import { useEffect, useState } from "react";

const CSS = [
  "https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700",
  "/assets/plugins/global/plugins.bundle.css",
  "/assets/css/style.bundle.css",
];

const JS = [
  "/assets/plugins/global/plugins.bundle.js",
  "/assets/js/scripts.bundle.js",
];

/**
 * Loads the existing Metronic theme assets (the same bundles the Blade layout
 * uses) only while a portal page is mounted, so the rest of the app is untouched.
 */
export function useMetronic() {
  const [ready, setReady] = useState(false);

  useEffect(() => {
    const links: HTMLLinkElement[] = CSS.map((href) => {
      const existing = document.querySelector<HTMLLinkElement>(
        `link[data-metronic="${href}"]`
      );
      if (existing) return existing;
      const el = document.createElement("link");
      el.rel = "stylesheet";
      el.href = href;
      el.dataset.metronic = href;
      document.head.appendChild(el);
      return el;
    });

    document.body.id = "kt_app_body";
    document.body.setAttribute("data-kt-app-header-fixed-mobile", "true");
    document.body.setAttribute("data-kt-app-toolbar-enabled", "true");
    document.body.classList.add("app-default");
    document.body.style.background = "#f9f9f9";
    document.body.style.color = "#252f4a";
    document.documentElement.setAttribute("data-bs-theme", "light");


    let cancelled = false;

    const loadScript = (src: string) =>
      new Promise<void>((resolve) => {
        const existing = document.querySelector<HTMLScriptElement>(
          `script[data-metronic="${src}"]`
        );
        if (existing) return resolve();
        const el = document.createElement("script");
        el.src = src;
        el.dataset.metronic = src;
        el.onload = () => resolve();
        el.onerror = () => resolve();
        document.body.appendChild(el);
      });

    (async () => {
      for (const src of JS) await loadScript(src);
      if (!cancelled) setReady(true);
    })();

    return () => {
      cancelled = true;
      links.forEach((el) => el.remove());
      document.body.classList.remove("app-default");
      document.body.removeAttribute("id");
    };
  }, []);

  // Re-initialise Metronic menu/drawer/sticky components for React-rendered DOM
  useEffect(() => {
    if (!ready) return;
    const w = window as any;
    const t = window.setTimeout(() => {
      w.KTComponents?.init?.();
      w.KTMenu?.createInstances?.();
      w.KTDrawer?.createInstances?.();
      w.KTSticky?.createInstances?.();
    }, 50);
    return () => window.clearTimeout(t);
  }, [ready]);

  return ready;
}
