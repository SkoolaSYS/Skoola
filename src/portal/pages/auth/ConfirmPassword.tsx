import { FormEvent, useState } from "react";
import { useNavigate } from "react-router-dom";
import { GuestLayout } from "../../layouts/GuestLayout";
import { api, ApiError } from "../../api";

export default function ConfirmPassword() {
  const navigate = useNavigate();
  const [password, setPassword] = useState("");
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [submitting, setSubmitting] = useState(false);

  const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setSubmitting(true);
    setErrors({});
    try {
      await api("/user/confirm-password", { method: "POST", body: { password }, form: true });
      navigate(-1);
    } catch (err) {
      if (err instanceof ApiError && err.errors) setErrors(err.errors);
      else setErrors({ password: ["Invalid password."] });
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <GuestLayout title="Confirm Password">
      <div className="text-center mb-11">
        <h1 className="text-dark fw-bolder mb-3">Confirm Password</h1>
      </div>

      <div className="text-gray-500 fw-semibold fs-6 mb-4">
        This is a secure area of the application. Please confirm your password before continuing.
      </div>

      <form className="form w-100" onSubmit={handleSubmit}>
        <div className="fv-row mb-8">
          <label className="form-label">Password</label>
          <input
            id="password"
            type="password"
            name="password"
            className="form-control bg-transparent"
            required
            autoComplete="current-password"
            autoFocus
            value={password}
            onChange={(e) => setPassword(e.target.value)}
          />
          {errors.password && <div className="text-danger fs-7 mt-1">{errors.password[0]}</div>}
        </div>

        <div className="d-flex justify-content-end mt-4">
          <button type="submit" className="btn btn-primary" disabled={submitting}>
            {submitting ? "Please wait..." : "Confirm"}
          </button>
        </div>
      </form>
    </GuestLayout>
  );
}
