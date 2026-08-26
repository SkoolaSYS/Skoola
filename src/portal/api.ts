/**
 * Thin client for the EXISTING Laravel backend.
 * The backend is untouched: we call the same routes the Blade app uses,
 * with session cookies (Sanctum/Fortify) and CSRF handling.
 */

export const API_BASE =
  (import.meta.env.VITE_LARAVEL_URL as string | undefined)?.replace(/\/$/, "") ||
  "";

function getCookie(name: string): string | null {
  const match = document.cookie.match(new RegExp("(^|; )" + name + "=([^;]*)"));
  return match ? decodeURIComponent(match[2]) : null;
}

export async function csrf(): Promise<void> {
  try {
    await fetch(`${API_BASE}/sanctum/csrf-cookie`, { credentials: "include" });
  } catch {
    /* backend may be offline in preview */
  }
}

export interface RequestOptions {
  method?: string;
  body?: unknown;
  headers?: Record<string, string>;
  /** send as form-encoded (Laravel web routes) instead of JSON */
  form?: boolean;
}

export class ApiError extends Error {
  status: number;
  errors?: Record<string, string[]>;
  constructor(message: string, status: number, errors?: Record<string, string[]>) {
    super(message);
    this.status = status;
    this.errors = errors;
  }
}

export async function api<T = unknown>(
  path: string,
  options: RequestOptions = {}
): Promise<T> {
  const { method = "GET", body, headers = {}, form = false } = options;

  if (method !== "GET" && !getCookie("XSRF-TOKEN")) {
    await csrf();
  }

  const token = getCookie("XSRF-TOKEN");
  const init: RequestInit = {
    method,
    credentials: "include",
    headers: {
      Accept: "application/json",
      "X-Requested-With": "XMLHttpRequest",
      ...(token ? { "X-XSRF-TOKEN": token } : {}),
      ...headers,
    },
  };

  if (body !== undefined) {
    if (form) {
      const fd = new URLSearchParams();
      Object.entries(body as Record<string, unknown>).forEach(([k, v]) => {
        if (v !== undefined && v !== null) fd.append(k, String(v));
      });
      (init.headers as Record<string, string>)["Content-Type"] =
        "application/x-www-form-urlencoded";
      init.body = fd.toString();
    } else if (body instanceof FormData) {
      init.body = body;
    } else {
      (init.headers as Record<string, string>)["Content-Type"] = "application/json";
      init.body = JSON.stringify(body);
    }
  }

  const res = await fetch(`${API_BASE}${path}`, init);
  const text = await res.text();
  let data: unknown = null;
  try {
    data = text ? JSON.parse(text) : null;
  } catch {
    data = text;
  }

  if (!res.ok) {
    const payload = (data ?? {}) as { message?: string; errors?: Record<string, string[]> };
    throw new ApiError(payload.message || res.statusText, res.status, payload.errors);
  }

  return data as T;
}
