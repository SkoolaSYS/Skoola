import { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api, ApiError } from "@/portal/api";

export default function TeacherCreate() {
  const navigate = useNavigate();
  const [errors, setErrors] = useState<string[]>([]);
  const [submitting, setSubmitting] = useState(false);

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setSubmitting(true);
    setErrors([]);
    const fd = new FormData(e.currentTarget);
    const body: Record<string, unknown> = {};
    fd.forEach((v, k) => (body[k] = v));
    try {
      await api("/school/teachers", { method: "POST", body, form: true });
      navigate("/school/teachers");
    } catch (err) {
      if (err instanceof ApiError && err.errors) {
        setErrors(Object.values(err.errors).flat());
      } else {
        setErrors(["Unable to register teacher."]);
      }
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <AppLayout title="Add Teacher" role="school">
      <div className="container mt-4">
        <div className="card">
          <div className="card-body">
            <h4 className="card-title mb-4">Register New Teacher</h4>

            {errors.length > 0 && (
              <div className="alert alert-danger mb-4">
                <ul className="mb-0">
                  {errors.map((e, i) => <li key={i}>{e}</li>)}
                </ul>
              </div>
            )}

            <form onSubmit={handleSubmit}>
              <div className="form-group mb-3">
                <label htmlFor="name">Full Name</label>
                <input type="text" name="name" id="name" className="form-control" required />
              </div>

              <div className="form-group mb-3">
                <label htmlFor="teacher_id">Teacher ID</label>
                <input type="text" name="teacher_id" id="teacher_id" className="form-control" required />
              </div>

              <div className="form-group mb-3">
                <label htmlFor="ic">IC</label>
                <input type="text" name="ic" id="ic" className="form-control" required />
              </div>

              <div className="form-group mb-3">
                <label htmlFor="address">Address</label>
                <textarea name="address" id="address" className="form-control" rows={2} required></textarea>
              </div>

              <div className="form-group mb-3">
                <label htmlFor="phone_num">Phone Number</label>
                <input type="text" name="phone_num" id="phone_num" className="form-control" required />
              </div>

              <div className="form-group mb-3">
                <label htmlFor="email">Email</label>
                <input type="email" name="email" id="email" className="form-control" required />
              </div>

              <div className="form-group mb-3">
                <label htmlFor="password">Password</label>
                <input type="password" name="password" id="password" className="form-control" required />
              </div>

              <div className="form-group mb-3">
                <label htmlFor="password_confirmation">Repeat Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" className="form-control" required />
              </div>

              <input type="hidden" name="role" value="teacher" />

              <div className="d-flex justify-content-between">
                <button type="submit" className="btn btn-success" disabled={submitting}>Register Teacher</button>
                <Link to="/school/teachers" className="btn btn-light">Cancel</Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
