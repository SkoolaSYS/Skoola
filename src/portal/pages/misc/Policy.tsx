import { useEffect, useState } from "react";
import { api } from "@/portal/api";

export default function Policy() {
  const [policy, setPolicy] = useState<string>("");
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    document.title = "Privacy Policy";
    let cancelled = false;
    api<{ policy: string }>("/policy")
      .then((res) => {
        if (!cancelled) setPolicy(res.policy ?? "");
      })
      .catch(() => {
        if (!cancelled) setPolicy("");
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
          ) : policy ? (
            <div dangerouslySetInnerHTML={{ __html: policy }} />
          ) : (
            <div className="text-muted">Privacy Policy could not be loaded.</div>
          )}
        </div>
      </div>
    </div>
  );
}
