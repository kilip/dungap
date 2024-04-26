import { providerMap } from "@/app/auth";
import { Container, Heading } from "@radix-ui/themes";
import { signIn } from "next-auth/react";

export default function Page() {
  return (
    <Container>
      <Heading>Login to dungap</Heading>
      {Object.values(providerMap).map((provider) => (
        <form
          key={provider.id}
          action={async () => {
            "use server";
            await signIn(provider.id);
          }}
        >
          <button type="submit">
            <span>Sign in with {provider.name}</span>
          </button>
        </form>
      ))}
    </Container>
  );
}
