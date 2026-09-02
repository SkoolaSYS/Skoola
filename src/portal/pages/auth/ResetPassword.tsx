import { FormEvent, useState } from "react";
import { useNavigate, useParams, useSearchParams } from "react-router-dom";
import { GuestLayout } from "../../layouts/GuestLayout";
import { api, ApiError } from "../../api";

export default function ResetPassword() {
  const { token } = useParams<{ token: string }>();
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();

  const [email, setEmail] = useState(searchParams.get("email") || "");
  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [submitting, setSubmitting] = useState(false);

  const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setSubmitting(true);
    setErrors({});
    try {
      await api("/reset-password", {
        method: "POST",
        body: {
          token: token || "",
          email,
          password,
          password_confirmation: passwordConfirmation,
        },
        form: true,
      });
      navigate("/login");
    } catch (err) {
      if (err instanceof ApiError && err.errors) setErrors(err.errors);
      else setErrors({ general: ["Unable to reset password."] });
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <GuestLayout title="Reset Password">
      <div className="text-center mb-11">
        <h1 className="text-dark fw-bolder mb-3">Reset Password</h1>
      </div>

      {errors.general && <div className="alert alert-danger">{errors.general[0]}</div>}

      <form className="form w-100" onSubmit={handleSubmit}>
        <div className="fv-row mb-8">
          <label className="form-label">Email</label>
          <input
            id="email"
            type="email"
            name="email"
            className="form-control bg-transparent"
            required
            autoFocus
            autoComplete="username"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
          />
          {errors.email && <div className="text-danger fs-7 mt-1">{errors.email[0]}</div>}
        </div>

        <div className="fv-row mb-8">
          <label className="form-label">Password</label>
          <input
            id="password"
            type="password"
            name="password"
            className="form-control bg-transparent"
            required
            autoComplete="new-password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
          />
          {errors.password && <div className="text-danger fs-7 mt-1">{errors.password[0]}</div>}
        </div>

        <div className="fv-row mb-8">
          <label className="form-label">Confirm Password</label>
          <input
            id="password_confirmation"
            type="password"
            name="password_confirmation"
            className="form-control bg-transparent"
            required
            autoComplete="new-password"
            value={passwordConfirmation}
            onChange={(e) => setPasswordConfirmation(e.target.value)}
          />
        </div>

        <div className="d-grid mb-10">
          <button type="submit" className="btn btn-primary" disabled={submitting}>
            {submitting ? "Please wait..." : "Reset Password"}
          </button>
        </div>
      </form>
    </GuestLayout>
  );
}
