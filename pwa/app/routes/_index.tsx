import type { MetaFunction } from "@remix-run/node";

export const meta: MetaFunction = () => {
  return [
    { title: "Dungap - The Command Center for Homelab" },
    { name: "description", content: "Welcome to Dungap!" },
  ];
};

export default function Index() {
  return (
    <div style={{ fontFamily: "system-ui, sans-serif", lineHeight: "1.8" }}>
      <h1>Welcome to Dungap</h1>
    </div>
  );
}
