import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { Card } from "@/portal/components/Card";
import { api, ApiError } from "@/portal/api";

export default function StudentCreate() {
  const navigate = useNavigate();
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [submitting, setSubmitting] = useState(false);

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setSubmitting(true);
    const fd = new FormData(e.currentTarget);
    const body: Record<string, unknown> = {};
    fd.forEach((v, k) => (body[k] = v));
    try {
      await api("/school/student/store", { method: "POST", body, form: true });
      navigate("/school/dashboard");
    } catch (err) {
      if (err instanceof ApiError && err.errors) setErrors(err.errors);
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <AppLayout title="Add Student" role="school">
      <Card>
        <h3 className="mb-4">Add Student</h3>
        <form onSubmit={handleSubmit}>
          <div className="mb-3">
            <label className="form-label">Full Name</label>
            <input type="text" className="form-control" name="name" required />
          </div>

          <div className="mb-3">
            <label className="form-label">IC</label>
            <input type="text" className="form-control" name="ic" required />
          </div>

          <div className="mb-3">
            <label className="form-label">Birth Cert No</label>
            <input type="text" className="form-control" name="birth_cert_no" />
          </div>

          <div className="mb-3">
            <label className="form-label">Address</label>
            <input type="text" className="form-control" name="address" required />
          </div>

          <div className="mb-3">
            <label className="form-label">Date of Birth</label>
            <input type="date" className="form-control" name="dob" required />
          </div>

          <div className="mb-3">
            <label className="form-label">Gender</label>
            <select name="gender" className="form-select" required defaultValue="">
              <option value="">Select Gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>

          <div className="mb-3">
            <label className="form-label">Grade</label>
            <select name="grade" className="form-select" required defaultValue="">
              <option value="">Select Grade</option>
              {["Darjah 1", "Darjah 2", "Darjah 3", "Darjah 4", "Darjah 5", "Darjah 6",
                "Tingkatan 1", "Tingkatan 2", "Tingkatan 3", "Tingkatan 4", "Tingkatan 5", "Tingkatan 6"].map((g) => (
                <option key={g} value={g}>{g}</option>
              ))}
            </select>
          </div>

          <div className="mb-3">
            <label className="form-label">Class</label>
            <input type="text" className="form-control" name="class_name" required />
          </div>

          <div className="mb-3">
            <label className="form-label">Race</label>
            <select name="race" className="form-select" required defaultValue="">
              <option value="">Select Race</option>
              <option value="Malay">Malay</option>
              <option value="Chinese">Chinese</option>
              <option value="Indian">Indian</option>
              <option value="Others">Others</option>
            </select>
          </div>

          <div className="mb-3">
            <label className="form-label">Religion</label>
            <select name="religion" className="form-select" required defaultValue="">
              <option value="">Select Religion</option>
              <option value="Islam">Islam</option>
              <option value="Christianity">Christianity</option>
              <option value="Buddhism">Buddhism</option>
              <option value="Hinduism">Hinduism</option>
              <option value="Others">Others</option>
            </select>
          </div>

          <div className="mb-3">
            <label className="form-label">Nationality</label>
            <select name="nationality" className="form-select" required defaultValue="">
              <option value="">Select Nationality</option>
              <option value="Malaysian">Malaysian</option>
              <option value="Non-Malaysian">Non-Malaysian</option>
            </select>
          </div>

          <div className="mb-3">
            <label className="form-label">Orphan</label>
            <select name="orphan" className="form-select" required defaultValue="">
              <option value="">Select</option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>

          <div className="mb-3">
            <label className="form-label">OKU</label>
            <select name="oku" className="form-select" required defaultValue="">
              <option value="">Select</option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>

          <hr className="my-5" />
          <h3 className="mb-4 fw-bold">Maklumat Ibu Bapa/Penjaga</h3>

          <div className="mb-5">
            <h4 className="mb-4 fw-semibold">Bapa</h4>
            <div className="mb-3">
              <label className="form-label">Nama Bapa</label>
              <input type="text" className="form-control form-control-lg" name="father_name" />
            </div>
            <div className="mb-3">
              <label className="form-label">IC Bapa</label>
              <input type="text" className="form-control form-control-lg" name="father_ic" />
            </div>
            <div className="mb-3">
              <label className="form-label">No. Telefon Bapa</label>
              <input type="text" className="form-control form-control-lg" name="father_phone" />
            </div>
            <div className="mb-3">
              <label className="form-label">Email Bapa</label>
              <input type="email" className="form-control form-control-lg" name="father_email" />
            </div>
          </div>

          <div className="mb-5">
            <h4 className="mb-4 fw-semibold">Ibu</h4>
            <div className="mb-3">
              <label className="form-label">Nama Ibu</label>
              <input type="text" className="form-control form-control-lg" name="mother_name" />
            </div>
            <div className="mb-3">
              <label className="form-label">IC Ibu</label>
              <input type="text" className="form-control form-control-lg" name="mother_ic" />
            </div>
            <div className="mb-3">
              <label className="form-label">No. Telefon Ibu</label>
              <input type="text" className="form-control form-control-lg" name="mother_phone" />
            </div>
            <div className="mb-3">
              <label className="form-label">Email Ibu</label>
              <input type="email" className="form-control form-control-lg" name="mother_email" />
            </div>
          </div>

          <div className="mb-5">
            <h4 className="mb-4 fw-semibold">Penjaga</h4>
            <div className="mb-3">
              <label className="form-label">Nama Penjaga</label>
              <input type="text" className="form-control form-control-lg" name="guardian_name" />
            </div>
            <div className="mb-3">
              <label className="form-label">IC Penjaga</label>
              <input type="text" className="form-control form-control-lg" name="guardian_ic" />
            </div>
            <div className="mb-3">
              <label className="form-label">No. Telefon Penjaga</label>
              <input type="text" className="form-control form-control-lg" name="guardian_phone" />
            </div>
            <div className="mb-3">
              <label className="form-label">Email Penjaga</label>
              <input type="email" className="form-control form-control-lg" name="guardian_email" />
            </div>
          </div>

          {Object.keys(errors).length > 0 && (
            <div className="alert alert-danger">
              <ul className="mb-0">
                {Object.values(errors).flat().map((e, i) => <li key={i}>{e}</li>)}
              </ul>
            </div>
          )}

          <button type="submit" className="btn btn-primary" disabled={submitting}>Add Student</button>
          <a href="/school/dashboard" className="btn btn-secondary ms-2">Cancel</a>
        </form>
      </Card>
    </AppLayout>
  );
}
