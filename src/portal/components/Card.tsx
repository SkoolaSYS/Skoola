import { ReactNode } from "react";

/** React equivalent of resources/views/components/card.blade.php */
export function Card({ children }: { children: ReactNode }) {
  return (
    <div className="card mb-5 mb-xl-10">
      <div className="card-body">{children}</div>
    </div>
  );
}
