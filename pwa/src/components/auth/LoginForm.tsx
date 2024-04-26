import { signIn } from "@/app/auth";
import { Container } from "@radix-ui/themes";

export default function LoginForm() {
  const doLogin = async (formData: FormData) => {
    "use server";
    await signIn("credentials", {
      username: "toni",
      password: "change me",
    });
  };
  return (
    <Container size="4">
      <form action={doLogin}>
        <div>
          <label>Username / Email</label>
          <input type="text" name="username" />
        </div>
        <div>
          <label>Password</label>
          <input type="password" name="password" />
        </div>
        <button type="submit">Login</button>
      </form>
    </Container>
  );
}
