import { useEffect, useState } from "react";
import { useParams, useNavigate, Link } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api } from "@/portal/api";

interface SchoolClass {
  id: number;
  class_name: string;
  grade?: { grade_name: string };
}

interface TeacherForm {
  id: number;
  name: string;
  email: string;
  ic?: string;
  address?: string;
  phone_num?: string;
  status: string;
  classes: number[];
}

interface EditData {
  teacher: TeacherForm;
  classes: SchoolClass[];
}

export default function TeacherEdit() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [teacher, setTeacher] = useState<TeacherForm | null>(null);
  const [classes, setClasses] = useState<SchoolClass[]>([]);
  const [loading, setLoading] = useState(true);
  const [errors, setErrors] = useState<Record<string, string[]>>({});

  useEffect(() => {
    let cancelled = false;
    api<EditData>(`/school/teachers/${id}/edit`)
      .then((res) => {
        if (cancelled) return;
        setTeacher(res.teacher);
        setClasses(res.classes ?? []);
      })
      .catch(() => {
        if (!cancelled) {
          setTeacher(null);
          setClasses([]);
        }
      })
      .finally(() => {
        if (!cancelled) setLoading(false);
      });
    return () => {
      cancelled = true;
    };
  }, [id]);

  function update<K extends keyof TeacherForm>(key: K, value: TeacherForm[K]) {
    setTeacher((prev) => (prev ? { ...prev, [key]: value } : prev));
  }

  function toggleClass(classId: number) {
    setTeacher((prev) => {
      if (!prev) return prev;
      const has = prev.classes.includes(classId);
      return { ...prev, classes: has ? prev.classes.filter((c) => c !== classId) : [...prev.classes, classId] };
    });
  }

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    if (!teacher) return;
    try {
      await api(`/school/teachers/${id}`, { method: "PUT", body: teacher, form: true });
      navigate("/school/teachers");
    } catch {
      /* ignore */
    }
  }

  if (loading) {
    return (
      <AppLayout title="Edit Teacher" role="school">
        <p className="text-muted">Loading…</p>
      </AppLayout>
    );
  }

  if (!teacher) {
    return (
      <AppLayout title="Edit Teacher" role="school">
        <div className="alert alert-warning">Unable to load teacher data.</div>
      </AppLayout>
    );
  }

  return (
    <AppLayout title="Edit Teacher" role="school">
      <div className="container mt-4">
        <div className="bg-white p-4 rounded">
          <h3 className="mb-3">Edit Teacher</h3>

          <form onSubmit={handleSubmit}>
            <div className="mb-3">
              <label className="form-label">Full Name</label>
              <input type="text" className="form-control" value={teacher.name}
                onChange={(e) => update("name", e.target.value)} />
              {errors.name && <small className="text-danger">{errors.name[0]}</small>}
            </div>

            <div className="mb-3">
              <label className="form-label">Email</label>
              <input type="email" className="form-control" value={teacher.email}
                onChange={(e) => update("email", e.target.value)} />
            </div>

            <div className="mb-3">
              <label className="form-label">IC</label>
              <input type="text" className="form-control" value={teacher.ic ?? ""}
                onChange={(e) => update("ic", e.target.value)} />
            </div>

            <div className="mb-3">
              <label className="form-label">Address</label>
              <textarea className="form-control" rows={2} value={teacher.address ?? ""}
                onChange={(e) => update("address", e.target.value)} />
            </div>

            <div className="mb-3">
              <label className="form-label">Phone Number</label>
              <input type="text" className="form-control" value={teacher.phone_num ?? ""}
                onChange={(e) => update("phone_num", e.target.value)} />
            </div>

            <div className="mb-3">
              <label className="form-label">Assign Classes:</label>
              <select
                className="form-select"
                multiple
                value={teacher.classes.map(String)}
                onChange={(e) => {
                  const selected = Array.from(e.target.selectedOptions).map((o) => Number(o.value));
                  update("classes", selected);
                }}
              >
                {classes.map((c) => (
                  <option key={c.id} value={c.id}>
                    {c.class_name} ({c.grade?.grade_name})
                  </option>
                ))}
              </select>
              <small className="text-muted">Hold CTRL (or CMD) to select multiple.</small>
            </div>

            <div className="mb-3">
              <label className="form-label">Status</label>
              <select className="form-select" value={teacher.status}
                onChange={(e) => update("status", e.target.value)}>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>

            <button type="submit" className="btn btn-success">Save</button>
            <Link to="/school/teachers" className="btn btn-secondary ms-2">Cancel</Link>
          </form>
        </div>
      </div>
    </AppLayout>
  );
}
