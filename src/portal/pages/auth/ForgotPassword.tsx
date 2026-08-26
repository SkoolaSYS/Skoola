import { FormEvent, useState } from "react";
import { GuestLayout } from "../../layouts/GuestLayout";
import { api, ApiError } from "../../api";

export default function ForgotPassword() {
  const [email, setEmail] = useState("");
  const [status, setStatus] = useState<string | null>(null);
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [submitting, setSubmitting] = useState(false);

  const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setSubmitting(true);
    setErrors({});
    setStatus(null);
    try {
      const res = await api<{ message?: string }>("/forgot-password", {
        method: "POST",
        body: { email },
        form: true,
      });
      setStatus(res?.message || "Password reset link sent.");
    } catch (err) {
      if (err instanceof ApiError && err.errors) setErrors(err.errors);
      else setErrors({ email: ["Unable to send reset link."] });
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <GuestLayout title="Forgot Password">
      <div className="text-center mb-11">
        <h1 className="text-dark fw-bolder mb-3">Forgot Password?</h1>
        <div className="text-gray-500 fw-semibold fs-6">
          Forgot your password? No problem. Just let us know your email address and we will email
          you a password reset link that will allow you to choose a new one.
        </div>
      </div>

      {status && <div className="alert alert-success">{status}</div>}
      {errors.email && <div className="alert alert-danger">{errors.email[0]}</div>}

      <form className="form w-100" onSubmit={handleSubmit}>
        <div className="fv-row mb-8">
          <input
            id="email"
            type="email"
            name="email"
            className="form-control bg-transparent"
            placeholder="Email"
            required
            autoFocus
            autoComplete="username"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
          />
        </div>
        <div className="d-grid mb-10">
          <button type="submit" className="btn btn-primary" disabled={submitting}>
            {submitting ? "Please wait..." : "Email Password Reset Link"}
          </button>
        </div>
      </form>
    </GuestLayout>
  );
}
