import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api } from "@/portal/api";

interface SchoolClass {
  id: number;
  class_name: string;
}

interface SchoolGrade {
  id: number;
  grade_name: string;
  classes: SchoolClass[];
}

export default function EditClass() {
  const [grades, setGrades] = useState<SchoolGrade[]>([]);
  const [loading, setLoading] = useState(true);
  const [successMsg, setSuccessMsg] = useState<string | null>(null);
  const [classNames, setClassNames] = useState<Record<number, string>>({});
  const [newClasses, setNewClasses] = useState<Record<number, string[]>>({});
  const [deletedClasses, setDeletedClasses] = useState<number[]>([]);

  useEffect(() => {
    let cancelled = false;
    api<SchoolGrade[]>("/dashboard/edit-class")
      .then((res) => {
        if (cancelled) return;
        setGrades(res ?? []);
        const names: Record<number, string> = {};
        (res ?? []).forEach((g) => g.classes.forEach((c) => (names[c.id] = c.class_name)));
        setClassNames(names);
      })
      .catch(() => {
        if (!cancelled) setGrades([]);
      })
      .finally(() => {
        if (!cancelled) setLoading(false);
      });
    return () => {
      cancelled = true;
    };
  }, []);

  function addClassInput(gradeId: number) {
    setNewClasses((prev) => ({ ...prev, [gradeId]: [...(prev[gradeId] ?? []), ""] }));
  }

  function updateNewClass(gradeId: number, idx: number, value: string) {
    setNewClasses((prev) => {
      const arr = [...(prev[gradeId] ?? [])];
      arr[idx] = value;
      return { ...prev, [gradeId]: arr };
    });
  }

  function removeNewClass(gradeId: number, idx: number) {
    setNewClasses((prev) => {
      const arr = [...(prev[gradeId] ?? [])];
      arr.splice(idx, 1);
      return { ...prev, [gradeId]: arr };
    });
  }

  function removeExistingClass(classId: number, gradeId: number) {
    setDeletedClasses((prev) => [...prev, classId]);
    setGrades((prev) =>
      prev.map((g) => (g.id === gradeId ? { ...g, classes: g.classes.filter((c) => c.id !== classId) } : g))
    );
  }

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    const body: Record<string, unknown> = {};
    Object.entries(classNames).forEach(([id, name]) => {
      body[`class_name[${id}]`] = name;
    });
    Object.entries(newClasses).forEach(([gradeId, names]) => {
      names.filter(Boolean).forEach((name, idx) => {
        body[`new_class[${gradeId}][${idx}]`] = name;
      });
    });
    deletedClasses.forEach((id, idx) => {
      body[`delete_class[${idx}]`] = id;
    });
    try {
      await api("/dashboard/update-class-names", { method: "POST", body, form: true });
      setSuccessMsg("Class names updated successfully!");
    } catch {
      /* ignore */
    }
  }

  return (
    <AppLayout title="Edit Class Names" role="school">
      <div className="d-flex justify-content-between align-items-center mb-4">
        <h2 className="h4 mb-0">Edit Class Names</h2>
        <small className="text-muted">Manage classes under each activated grade</small>
      </div>

      <div className="py-4 bg-light min-vh-100">
        <div className="container">
          {successMsg && (
            <div className="alert alert-success alert-dismissible fade show" role="alert">
              {successMsg}
              <button type="button" className="btn-close" onClick={() => setSuccessMsg(null)}></button>
            </div>
          )}

          {loading ? (
            <p className="text-muted">Loading…</p>
          ) : (
            <form onSubmit={handleSubmit}>
              <div className="row g-4">
                {grades.map((grade) => (
                  <div className="col-12 col-md-6 col-lg-4" key={grade.id}>
                    <div className="card shadow-sm h-100">
                      <div className="card-body d-flex flex-column">
                        <h5 className="card-title">{grade.grade_name}</h5>

                        <div className="mb-3 existing-classes">
                          {grade.classes.map((cls) => (
                            <div className="input-group mb-2 class-row" key={cls.id}>
                              <input
                                type="text"
                                className="form-control form-control-sm"
                                placeholder="Class Name"
                                value={classNames[cls.id] ?? ""}
                                onChange={(e) =>
                                  setClassNames((prev) => ({ ...prev, [cls.id]: e.target.value }))
                                }
                              />
                              <button
                                type="button"
                                className="btn btn-outline-danger btn-sm"
                                onClick={() => removeExistingClass(cls.id, grade.id)}
                              >
                                ✕
                              </button>
                            </div>
                          ))}
                        </div>

                        <div className="mb-2 new-classes">
                          {(newClasses[grade.id] ?? []).map((val, idx) => (
                            <div className="input-group mb-2" key={idx}>
                              <input
                                type="text"
                                placeholder="New class name"
                                className="form-control form-control-sm"
                                value={val}
                                onChange={(e) => updateNewClass(grade.id, idx, e.target.value)}
                              />
                              <button
                                type="button"
                                className="btn btn-outline-danger btn-sm"
                                onClick={() => removeNewClass(grade.id, idx)}
                              >
                                ✕
                              </button>
                            </div>
                          ))}
                        </div>

                        <button
                          type="button"
                          className="btn btn-sm btn-outline-primary mt-auto"
                          onClick={() => addClassInput(grade.id)}
                        >
                          + Add New Class
                        </button>
                      </div>
                    </div>
                  </div>
                ))}
              </div>

              <div className="text-center mt-4">
                <button type="submit" className="btn btn-success btn-lg">
                  Save Changes
                </button>
              </div>
            </form>
          )}

          <div className="text-center mt-3">
            <Link to="/school/class-management" className="btn btn-link">Back to Class Management</Link>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
