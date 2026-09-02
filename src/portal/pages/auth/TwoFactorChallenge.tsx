import { FormEvent, useRef, useState } from "react";
import { useNavigate } from "react-router-dom";
import { GuestLayout } from "../../layouts/GuestLayout";
import { api, ApiError } from "../../api";

export default function TwoFactorChallenge() {
  const navigate = useNavigate();
  const [recovery, setRecovery] = useState(false);
  const [code, setCode] = useState("");
  const [recoveryCode, setRecoveryCode] = useState("");
  const [errors, setErrors] = useState<Record<string, string[]>>({});
  const [submitting, setSubmitting] = useState(false);
  const codeRef = useRef<HTMLInputElement>(null);
  const recoveryRef = useRef<HTMLInputElement>(null);

  const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setSubmitting(true);
    setErrors({});
    try {
      await api("/two-factor-challenge", {
        method: "POST",
        body: recovery ? { recovery_code: recoveryCode } : { code },
        form: true,
      });
      navigate("/dashboard");
    } catch (err) {
      if (err instanceof ApiError && err.errors) setErrors(err.errors);
      else setErrors({ code: ["Invalid code."] });
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <GuestLayout title="Two Factor Challenge">
      <div className="text-center mb-11">
        <h1 className="text-dark fw-bolder mb-3">Two Factor Authentication</h1>
      </div>

      {!recovery ? (
        <div className="text-gray-500 fw-semibold fs-6 mb-4">
          Please confirm access to your account by entering the authentication code provided by
          your authenticator application.
        </div>
      ) : (
        <div className="text-gray-500 fw-semibold fs-6 mb-4">
          Please confirm access to your account by entering one of your emergency recovery codes.
        </div>
      )}

      <form className="form w-100" onSubmit={handleSubmit}>
        {!recovery ? (
          <div className="fv-row mb-8">
            <label className="form-label">Code</label>
            <input
              id="code"
              type="text"
              inputMode="numeric"
              name="code"
              className="form-control bg-transparent"
              autoFocus
              autoComplete="one-time-code"
              ref={codeRef}
              value={code}
              onChange={(e) => setCode(e.target.value)}
            />
            {errors.code && <div className="text-danger fs-7 mt-1">{errors.code[0]}</div>}
          </div>
        ) : (
          <div className="fv-row mb-8">
            <label className="form-label">Recovery Code</label>
            <input
              id="recovery_code"
              type="text"
              name="recovery_code"
              className="form-control bg-transparent"
              autoComplete="one-time-code"
              ref={recoveryRef}
              value={recoveryCode}
              onChange={(e) => setRecoveryCode(e.target.value)}
            />
            {errors.recovery_code && (
              <div className="text-danger fs-7 mt-1">{errors.recovery_code[0]}</div>
            )}
          </div>
        )}

        <div className="d-flex align-items-center justify-content-end gap-4 mt-4">
          {!recovery ? (
            <button
              type="button"
              className="btn btn-link p-0"
              onClick={() => {
                setRecovery(true);
                window.setTimeout(() => recoveryRef.current?.focus(), 0);
              }}
            >
              Use a recovery code
            </button>
          ) : (
            <button
              type="button"
              className="btn btn-link p-0"
              onClick={() => {
                setRecovery(false);
                window.setTimeout(() => codeRef.current?.focus(), 0);
              }}
            >
              Use an authentication code
            </button>
          )}

          <button type="submit" className="btn btn-primary" disabled={submitting}>
            {submitting ? "Please wait..." : "Log in"}
          </button>
        </div>
      </form>
    </GuestLayout>
  );
}
