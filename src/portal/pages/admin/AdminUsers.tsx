import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { Card } from "@/portal/components/Card";
import { api } from "@/portal/api";

interface UserRow {
  id: number;
  name: string;
  email: string;
  phone_num?: string;
  roles?: string[];
}

export default function AdminUsers() {
  const [users, setUsers] = useState<UserRow[]>([]);
  const [loading, setLoading] = useState(true);
  const [success, setSuccess] = useState<string | null>(null);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    let cancelled = false;
    setLoading(true);
    api<{ users?: UserRow[]; success?: string; error?: string }>("/admin")
      .then((res) => {
        if (cancelled) return;
        setUsers(res.users ?? []);
        setSuccess(res.success ?? null);
        setError(res.error ?? null);
      })
      .catch(() => {
        if (!cancelled) setUsers([]);
      })
      .finally(() => {
        if (!cancelled) setLoading(false);
      });
    return () => {
      cancelled = true;
    };
  }, []);

  return (
    <AppLayout title="User List" role="admin">
      <Card>
        <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100 justify-content-end">
          <div className="page-title d-flex flex-column justify-content-center gap-2 me-3">
            <h1 className="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">User List</h1>
          </div>
          <div>
            <Link to="/admin/create" className="btn btn-primary">
              <i className="ki-duotone ki-plus fs-2" />Create User
            </Link>
          </div>
        </div>

        {success && <div className="alert alert-success">{success}</div>}
        {error && <div className="alert alert-danger">{error}</div>}

        <div className="py-12">
          <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div className="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
              {loading ? (
                <div className="text-muted">Loading…</div>
              ) : users.length === 0 ? (
                <div className="alert alert-info text-center">No users found.</div>
              ) : (
                <div className="table-responsive">
                  <table className="table table-row-bordered align-middle">
                    <thead>
                      <tr className="fw-bold text-muted">
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Roles</th>
                        <th className="text-end">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      {users.map((u) => (
                        <tr key={u.id}>
                          <td>{u.name}</td>
                          <td>{u.email}</td>
                          <td>{u.phone_num}</td>
                          <td>{(u.roles ?? []).join(", ")}</td>
                          <td className="text-end">
                            <Link to={`/admin/edit/${u.id}`} className="btn btn-sm btn-light-primary">Edit</Link>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              )}
            </div>
          </div>
        </div>
      </Card>
    </AppLayout>
  );
}
