import { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { AppLayout } from "@/portal/layouts/AppLayout";
import { api, ApiError } from "@/portal/api";
import StudentForm, { StudentFormData, emptyStudentForm } from "./StudentForm";

export default function StudentAdd() {
  const navigate = useNavigate();
  const [students, setStudents] = useState<StudentFormData[]>([emptyStudentForm()]);
  const [errors, setErrors] = useState<string[]>([]);
  const [saving, setSaving] = useState(false);

  const updateStudent = (index: number, field: keyof StudentFormData, value: string) => {
    setStudents((prev) =>
      prev.map((s, i) => (i === index ? { ...s, [field]: value } : s))
    );
  };

  const addStudent = () => setStudents((prev) => [...prev, emptyStudentForm()]);

  const removeStudent = (index: number) =>
    setStudents((prev) => prev.filter((_, i) => i !== index));

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrors([]);
    setSaving(true);

    const body: Record<string, string> = {};
    students.forEach((student, index) => {
      (Object.keys(student) as (keyof StudentFormData)[]).forEach((field) => {
        body[`students[${index}][${field}]`] = student[field];
      });
    });

    try {
      await api("/student/store", { method: "POST", body, form: true });
      navigate("/student");
    } catch (err) {
      if (err instanceof ApiError && err.errors) {
        setErrors(Object.values(err.errors).flat());
      } else {
        setErrors(["Unable to save student(s)."]);
      }
    } finally {
      setSaving(false);
    }
  };

  return (
    <AppLayout title="Add Student" role="parent">
      <div className="app-toolbar-wrapper d-flex align-items-center flex-stack flex-wrap gap-2 py-4 w-100">
        <div className="page-title d-flex flex-column justify-content-center gap-2 me-3">
          <h1 className="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 m-0">
            Student List
          </h1>
        </div>
      </div>

      <div className="card mb-5 mb-xl-10">
        <div className="card-header border-0">
          <div className="card-title m-0">
            <h3 className="fw-bold m-0">Student Profile Details</h3>
          </div>
        </div>

        <div id="kt_account_settings_profile_details" className="collapse show">
          <form id="kt_account_profile_details_form" className="form" onSubmit={handleSubmit}>
            {errors.length > 0 && (
              <div className="alert alert-danger">
                <ul className="mb-0">
                  {errors.map((error, i) => (
                    <li key={i}>{error}</li>
                  ))}
                </ul>
              </div>
            )}

            <div id="student-forms-container">
              {students.map((student, index) => (
                <div className="student-form-wrapper" key={index}>
                  <StudentForm
                    prefix={`students[${index}]`}
                    data={student}
                    onChange={(field, value) => updateStudent(index, field, value)}
                  />
                  {index > 0 && (
                    <button
                      type="button"
                      className="btn btn-danger mt-2 remove-student-btn"
                      onClick={() => removeStudent(index)}
                    >
                      Remove
                    </button>
                  )}
                </div>
              ))}
            </div>

            <button
              type="button"
              className="btn btn-secondary btn-sm mt-1"
              id="add-student-btn"
              onClick={addStudent}
            >
              Add More Student
            </button>

            <div className="card-footer d-flex justify-content-end py-6 px-9">
              <Link to="/student" className="btn btn-light btn-active-light-primary me-2">
                Cancel
              </Link>
              <button
                type="submit"
                className="btn btn-primary"
                id="kt_account_profile_details_submit"
                disabled={saving}
              >
                {saving ? "Saving..." : "Save"}
              </button>
            </div>
          </form>
        </div>
      </div>
    </AppLayout>
  );
}
