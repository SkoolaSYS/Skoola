import { useEffect, useState } from "react";
import { api } from "@/portal/api";

export default function Terms() {
  const [terms, setTerms] = useState<string>("");
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    document.title = "Terms of Service";
    let cancelled = false;
    api<{ terms: string }>("/terms")
      .then((res) => {
        if (!cancelled) setTerms(res.terms ?? "");
      })
      .catch(() => {
        if (!cancelled) setTerms("");
      })
      .finally(() => {
        if (!cancelled) setLoading(false);
      });
    return () => {
      cancelled = true;
    };
  }, []);

  return (
    <div className="pt-4 bg-gray-100">
      <div className="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
        <div>
          <img src="/assets/media/logos/logo.svg" alt="Logo" className="w-40" />
        </div>

        <div className="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose">
          {loading ? (
            <div className="text-muted">Loading…</div>
          ) : terms ? (
            <div dangerouslySetInnerHTML={{ __html: terms }} />
          ) : (
            <div className="text-muted">Terms of Service could not be loaded.</div>
          )}
        </div>
      </div>
    </div>
  );
}
