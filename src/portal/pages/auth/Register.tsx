import { FormEvent, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useMetronic } from "../../useMetronic";
import { api, ApiError } from "../../api";
import GuardianForm, { GuardianFormValue } from "./GuardianForm";
import "../../portal.css";

const emptyGuardian = (): GuardianFormValue => ({
  name: "",
  username: "",
  email: "",
  phone_num: "",
  ic: "",
  relationship: "",
  occupation: "",
  address: "",
  password: "",
  password_confirmation: "",
});

interface StudentInfo {
  name: string;
  ic: string;
  grade: string;
  class_name: string;
}

/** Mirrors auth/register.blade.php */
export default function Register() {
  const ready = useMetronic();
  const navigate = useNavigate();
  const [submitting, setSubmitting] = useState(false);
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [guardians, setGuardians] = useState<GuardianFormValue[]>([]);
  const [students, setStudents] = useState<StudentInfo[] | null>(null);

  const [name, setName] = useState("");
  const [username, setUsername] = useState("");
  const [email, setEmail] = useState("");
  const [phoneNum, setPhoneNum] = useState("");
  const [ic, setIc] = useState("");
  const [address, setAddress] = useState("");
  const [occupation, setOccupation] = useState("");
  const [relationship, setRelationship] = useState("");
  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");

  useEffect(() => {
    document.title = "Register | 3S Portal";
    document.body.classList.add(
      "app-blank",
      "bgi-size-cover",
      "bgi-attachment-fixed",
      "bgi-position-center",
      "portal-login-page",
    );
    return () => {
      document.body.classList.remove(
        "app-blank",
        "bgi-size-cover",
        "bgi-attachment-fixed",
        "bgi-position-center",
        "portal-login-page",
      );
    };
  }, []);

  // Auto fetch students by parent IC (mirrors blade's inline script)
  useEffect(() => {
    if (ic.length !== 12) {
      setStudents(null);
      return;
    }
    const t = window.setTimeout(() => {
      api<StudentInfo[]>(`/get-students-by-parent-ic?ic=${encodeURIComponent(ic)}`)
        .then((data) => setStudents(data))
        .catch(() => setStudents(null));
    }, 500);
    return () => window.clearTimeout(t);
  }, [ic]);

  const addGuardian = () => setGuardians((g) => [...g, emptyGuardian()]);
  const removeGuardian = (index: number) =>
    setGuardians((g) => g.filter((_, i) => i !== index));
  const updateGuardian = (index: number, field: keyof GuardianFormValue, value: string) =>
    setGuardians((g) => g.map((row, i) => (i === index ? { ...row, [field]: value } : row)));

  const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setSubmitting(true);
    setErrors({});

    const body: Record<string, string> = {
      name,
      username,
      email,
      phone_num: phoneNum,
      ic,
      address,
      occupation,
      relationship,
      password,
      password_confirmation: passwordConfirmation,
    };

    guardians.forEach((guardian, index) => {
      Object.entries(guardian).forEach(([key, value]) => {
        body[`guardians[${index}][${key}]`] = value;
      });
    });

    try {
      await api("/register", { method: "POST", body, form: true });
      navigate("/dashboard");
    } catch (err) {
      if (err instanceof ApiError && err.errors) {
        setErrors(err.errors);
      } else {
        setErrors({ general: ["Unable to register. Please try again."] });
      }
    } finally {
      setSubmitting(false);
    }
  };

  const fieldError = (field: string) => errors[field]?.[0];

  if (!ready) {
    return <div className="min-vh-100 d-flex flex-center text-muted">Loading…</div>;
  }

  return (
    <main className="d-flex flex-column flex-root" id="kt_app_root">
      <div className="d-flex flex-column flex-lg-row flex-column-fluid">
        <section className="d-flex flex-lg-row-fluid" aria-label="3S Portal introduction">
          <div className="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
            <img
              className="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
              src="/assets/media/auth/agency.png"
              alt=""
            />
            <img
              className="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
              src="/assets/media/auth/agency-dark.png"
              alt=""
            />
            <h1 className="text-gray-800 fs-2qx fw-bold text-center mb-7">
              Fast, Efficient and Productive
            </h1>
          </div>
        </section>

        <section className="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
          <div className="bg-body d-flex flex-column flex-center rounded-4 w-md-700px p-10">
            <div className="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-600px">
              <div className="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">
                <form className="form w-100" onSubmit={handleSubmit}>
                  <div className="text-center mb-11">
                    <h1 className="text-dark fw-bolder mb-3">Register</h1>
                  </div>

                  {errors.general && (
                    <div className="alert alert-danger">{errors.general[0]}</div>
                  )}

                  <div id="parent-form">
                    <h3>Parent Details</h3>
                    <div className="card-body border-top p-9">
                      <div className="row mb-6">
                        <label className="col-lg-4 col-form-label required">Full Name</label>
                        <div className="col-lg-8 fv-row">
                          <input
                            type="text"
                            name="name"
                            className="form-control"
                            placeholder="Full Name"
                            required
                            value={name}
                            onChange={(e) => setName(e.target.value)}
                          />
                          {fieldError("name") && (
                            <div className="text-danger fs-7 mt-1">{fieldError("name")}</div>
                          )}
                        </div>
                      </div>

                      <div className="row mb-6">
                        <label className="col-lg-4 col-form-label required">Username</label>
                        <div className="col-lg-8 fv-row">
                          <input
                            type="text"
                            name="username"
                            className="form-control"
                            placeholder="Username"
                            required
                            value={username}
                            onChange={(e) => setUsername(e.target.value)}
                          />
                          {fieldError("username") && (
                            <div className="text-danger fs-7 mt-1">{fieldError("username")}</div>
                          )}
                        </div>
                      </div>

                      <div className="row mb-6">
                        <label className="col-lg-4 col-form-label required">Email</label>
                        <div className="col-lg-8 fv-row">
                          <input
                            type="email"
                            name="email"
                            className="form-control"
                            placeholder="Email"
                            required
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                          />
                          {fieldError("email") && (
                            <div className="text-danger fs-7 mt-1">{fieldError("email")}</div>
                          )}
                        </div>
                      </div>

                      <div className="row mb-6">
                        <label className="col-lg-4 col-form-label required">Phone Number</label>
                        <div className="col-lg-8 fv-row">
                          <input
                            type="text"
                            name="phone_num"
                            className="form-control"
                            placeholder="Phone Number"
                            required
                            value={phoneNum}
                            onChange={(e) => setPhoneNum(e.target.value)}
                          />
                        </div>
                      </div>

                      <div className="row mb-6">
                        <label className="col-lg-4 col-form-label required">IC</label>
                        <div className="col-lg-8 fv-row">
                          <input
                            type="text"
                            id="parent_ic"
                            name="ic"
                            className="form-control"
                            placeholder="IC"
                            required
                            value={ic}
                            onChange={(e) => setIc(e.target.value)}
                          />
                          {fieldError("ic") && (
                            <div className="text-danger fs-7 mt-1">{fieldError("ic")}</div>
                          )}
                        </div>
                      </div>

                      <div className="row mb-6">
                        <label className="col-lg-4 col-form-label required">Address</label>
                        <div className="col-lg-8 fv-row">
                          <input
                            type="text"
                            name="address"
                            className="form-control"
                            placeholder="Address"
                            required
                            value={address}
                            onChange={(e) => setAddress(e.target.value)}
                          />
                        </div>
                      </div>

                      <div className="row mb-6">
                        <label className="col-lg-4 col-form-label required">State / City / Postcode</label>
                        <div className="col-lg-8 fv-row d-flex gap-3">
                          <input type="text" name="state" className="form-control" placeholder="State" />
                          <input type="text" name="city" className="form-control" placeholder="City" />
                          <input type="text" name="postcode" className="form-control" placeholder="Postcode" />
                        </div>
                      </div>

                      <div className="row mb-6">
                        <label className="col-lg-4 col-form-label required">Occupation</label>
                        <div className="col-lg-8 fv-row">
                          <input
                            type="text"
                            name="occupation"
                            className="form-control"
                            placeholder="Occupation"
                            required
                            value={occupation}
                            onChange={(e) => setOccupation(e.target.value)}
                          />
                        </div>
                      </div>

                      <div className="row mb-6">
                        <label className="col-lg-4 col-form-label required">Relationship</label>
                        <div className="col-lg-8 fv-row">
                          <select
                            name="relationship"
                            className="form-control"
                            required
                            value={relationship}
                            onChange={(e) => setRelationship(e.target.value)}
                          >
                            <option value="">Select Relationship</option>
                            <option value="Father">Father</option>
                            <option value="Mother">Mother</option>
                            <option value="Guardian">Guardian</option>
                          </select>
                        </div>
                      </div>

                      <div className="row mb-6" data-kt-password-meter="true">
                        <label className="col-lg-4 col-form-label required">Password</label>
                        <div className="col-lg-8 fv-row">
                          <div className="mb-1">
                            <div className="position-relative mb-3">
                              <input
                                type="password"
                                placeholder="Password"
                                name="password"
                                className="form-control bg-transparent"
                                required
                                autoComplete="new-password"
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                              />
                            </div>
                          </div>
                          <div className="text-muted">
                            Use 8 or more characters with a mix of letters, numbers &amp; symbols.
                          </div>
                          {fieldError("password") && (
                            <div className="text-danger fs-7 mt-1">{fieldError("password")}</div>
                          )}
                        </div>
                      </div>

                      <div className="row mb-6">
                        <label className="col-lg-4 col-form-label required">Repeat Password</label>
                        <div className="col-lg-8 fv-row">
                          <input
                            type="password"
                            placeholder="Repeat Password"
                            name="password_confirmation"
                            className="form-control bg-transparent"
                            required
                            autoComplete="new-password"
                            value={passwordConfirmation}
                            onChange={(e) => setPasswordConfirmation(e.target.value)}
                          />
                        </div>
                      </div>

                      <div id="additional-guardians-container">
                        {guardians.map((guardian, index) => (
                          <GuardianForm
                            key={index}
                            index={index}
                            value={guardian}
                            onChange={updateGuardian}
                            onRemove={removeGuardian}
                          />
                        ))}
                      </div>
                      <button
                        type="button"
                        className="btn btn-secondary w-100 mb-4"
                        id="add-guardian-btn"
                        onClick={addGuardian}
                      >
                        Add Guardian
                      </button>

                      <div id="student-forms-container">
                        {students === null ? null : students.length === 0 ? (
                          <div className="alert alert-warning">No children found for this IC.</div>
                        ) : (
                          students.map((student, index) => (
                            <div className="card p-4 mb-3" key={index}>
                              <h5>Child {index + 1}</h5>
                              <p>
                                <strong>Name:</strong> {student.name}
                              </p>
                              <p>
                                <strong>IC:</strong> {student.ic}
                              </p>
                              <p>
                                <strong>Grade:</strong> {student.grade}
                              </p>
                              <p>
                                <strong>Class:</strong> {student.class_name}
                              </p>
                            </div>
                          ))
                        )}
                      </div>

                      <div className="d-grid mb-10">
                        <button
                          type="submit"
                          id="kt_sign_up_submit"
                          className="btn btn-primary"
                          disabled={submitting}
                        >
                          <span className={submitting ? "d-none" : "indicator-label"}>Register</span>
                          {submitting && (
                            <span className="indicator-label">
                              Please wait...
                              <span className="spinner-border spinner-border-sm align-middle ms-2" />
                            </span>
                          )}
                        </button>
                      </div>

                      <div className="text-gray-500 text-center fw-semibold fs-6">
                        Already have an account?{" "}
                        <a href="/login" className="link-primary fw-semibold">
                          Log In
                        </a>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </section>
      </div>
    </main>
  );
}
