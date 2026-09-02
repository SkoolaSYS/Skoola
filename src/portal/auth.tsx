import { createContext, ReactNode, useCallback, useContext, useEffect, useState } from "react";
import { api, csrf } from "./api";

export type Role = "parent" | "teacher" | "school" | "ppd" | "state" | "country" | "admin";

export interface AuthUser {
  id: number;
  name: string;
  email: string;
  roles?: string[];
  role?: Role;
  [key: string]: unknown;
}

interface AuthState {
  user: AuthUser | null;
  role: Role;
  loading: boolean;
  login: (email: string, password: string, remember?: boolean) => Promise<void>;
  logout: () => Promise<void>;
  refresh: () => Promise<void>;
}

const AuthContext = createContext<AuthState | null>(null);

function resolveRole(user: AuthUser | null): Role {
  if (!user) return "parent";
  const raw = user.role ?? user.roles?.[0];
  const known: Role[] = ["parent", "teacher", "school", "ppd", "state", "country", "admin"];
  return (known.find((r) => r === raw) ?? "parent") as Role;
}

export function AuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState<AuthUser | null>(null);
  const [loading, setLoading] = useState(true);

  const refresh = useCallback(async () => {
    try {
      const me = await api<AuthUser>("/api/user");
      setUser(me);
    } catch {
      setUser(null);
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    void refresh();
  }, [refresh]);

  const login = useCallback(
    async (email: string, password: string, remember = false) => {
      await csrf();
      // Fortify's login route on the existing Laravel backend
      await api("/login", { method: "POST", body: { email, password, remember }, form: true });
      await refresh();
    },
    [refresh]
  );

  const logout = useCallback(async () => {
    try {
      await api("/logout", { method: "POST", body: {}, form: true });
    } catch {
      /* ignore */
    }
    setUser(null);
  }, []);

  return (
    <AuthContext.Provider
      value={{ user, role: resolveRole(user), loading, login, logout, refresh }}
    >
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth(): AuthState {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error("useAuth must be used within AuthProvider");
  return ctx;
}
