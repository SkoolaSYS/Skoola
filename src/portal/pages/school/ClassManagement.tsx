import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api } from "@/portal/api";

interface ClassManagementData {
  allGrades: string[];
  activatedGrades: string[];
}

export default function ClassManagement() {
  const [allGrades, setAllGrades] = useState<string[]>([]);
  const [activatedGrades, setActivatedGrades] = useState<string[]>([]);
  const [newGradeName, setNewGradeName] = useState("");
  const [successMsg, setSuccessMsg] = useState<string | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let cancelled = false;
    api<ClassManagementData>("/dashboard/class-management")
      .then((res) => {
        if (cancelled) return;
        setAllGrades(res.allGrades ?? []);
        setActivatedGrades(res.activatedGrades ?? []);
      })
      .catch(() => {
        if (!cancelled) {
          setAllGrades([]);
          setActivatedGrades([]);
        }
      })
      .finally(() => {
        if (!cancelled) setLoading(false);
      });
    return () => {
      cancelled = true;
    };
  }, []);

  async function activateGrade(grade: string) {
    try {
      await api("/dashboard/activate-grade", { method: "POST", body: { grade_name: grade }, form: true });
      setActivatedGrades((prev) => [...prev, grade]);
      setSuccessMsg("Grade activated successfully.");
    } catch {
      /* ignore, backend may be unreachable */
    }
  }

  async function deactivateGrade(grade: string) {
    try {
      await api("/dashboard/school/deactivate-grade", { method: "DELETE", body: { grade_name: grade }, form: true });
      setActivatedGrades((prev) => prev.filter((g) => g !== grade));
      setSuccessMsg("Grade deactivated successfully.");
    } catch {
      /* ignore */
    }
  }

  async function addGrade(e: React.FormEvent) {
    e.preventDefault();
    const gradeName = newGradeName.trim();
    if (!gradeName) return;
    try {
      const res = await api<{ success?: string }>("/dashboard/school/add-grade", {
        method: "POST",
        body: { grade_name: gradeName },
      });
      if (res.success) {
        setSuccessMsg(res.success);
        setAllGrades((prev) => (prev.includes(gradeName) ? prev : [...prev, gradeName]));
        setActivatedGrades((prev) => (prev.includes(gradeName) ? prev : [...prev, gradeName]));
        setNewGradeName("");
      }
    } catch {
      /* ignore */
    }
  }

  return (
    <AppLayout title="Class Management" role="school">
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2 className="h4 mb-0">Class Management</h2>
        <small className="text-muted">Manage grade activation & class settings</small>
      </div>

      <div className="py-4 bg-light min-vh-100">
        <div className="container">
          {successMsg && (
            <div className="alert alert-success alert-dismissible fade show" role="alert">
              {successMsg}
              <button type="button" className="btn-close" onClick={() => setSuccessMsg(null)}></button>
            </div>
          )}

          <div className="card shadow-sm rounded-3 p-4">
            <div className="mb-4">
              <h5 className="card-title mb-1">Available Grades</h5>
              <small className="text-muted">Activate grades to allow class management.</small>
            </div>

            <form className="row g-2 mb-4" onSubmit={addGrade}>
              <div className="col-auto">
                <input
                  type="text"
                  className="form-control form-control-sm"
                  placeholder="Add new grade"
                  required
                  value={newGradeName}
                  onChange={(e) => setNewGradeName(e.target.value)}
                />
              </div>
              <div className="col-auto">
                <button type="submit" className="btn btn-success btn-sm">Add Grade</button>
              </div>
            </form>

            <div className="row g-3">
              {loading && <p className="text-muted">Loading…</p>}
              {!loading && allGrades.length === 0 && <p className="text-muted">No grades available.</p>}
              {allGrades.map((grade) => (
                <div className="col-12 col-sm-6 col-md-4 col-lg-3" key={grade}>
                  <div className="card h-100 border-1 shadow-sm">
                    <div className="card-body d-flex flex-column justify-content-between">
                      <h6 className="card-title">{grade}</h6>
                      {activatedGrades.includes(grade) ? (
                        <div className="d-grid gap-2 mt-2">
                          <span className="badge bg-success mb-2">✓ Activated</span>
                          <button
                            type="button"
                            className="btn btn-outline-danger btn-sm"
                            onClick={() => deactivateGrade(grade)}
                          >
                            Deactivate Grade
                          </button>
                        </div>
                      ) : (
                        <div className="d-grid gap-2 mt-2">
                          <button
                            type="button"
                            className="btn btn-success btn-sm"
                            onClick={() => activateGrade(grade)}
                          >
                            Activate Grade
                          </button>
                        </div>
                      )}
                    </div>
                  </div>
                </div>
              ))}
            </div>

            <hr className="my-4" />
            <div className="text-center">
              <Link to="/school/class/edit-class/edit" className="btn btn-primary rounded-pill">
                Edit Class Names
              </Link>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
