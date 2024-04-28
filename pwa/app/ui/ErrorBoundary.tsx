import { Button, Card, Heading, Text } from "@radix-ui/themes";
import {
  isRouteErrorResponse,
  useNavigate,
  useRouteError,
} from "@remix-run/react";

export function getErrorMessage(error: unknown) {
  if (typeof error === "string") return error;
  if (
    error &&
    typeof error === "object" &&
    "message" in error &&
    typeof error.message === "string"
  ) {
    return error.message;
  }
  console.error("Unable to get error message for error", error);
  return "Unknown Error";
}

export function GeneralErrorBoundary() {
  const error = useRouteError();
  const navigate = useNavigate();
  return (
    <div className="flex items-center justify-center min-h-screen">
      <Card>
        {isRouteErrorResponse(error) && (
          <div className="flex flex-col">
            <Heading color="red" className="border-b-gray border-b-2 p-2">
              Oops!
            </Heading>
            <Text as="p" className="px-4" size="3">
              Looks like you are lost!
              <br />
              Please go back to dashboard.
            </Text>
          </div>
        )}
        <div className="p-4">
          <Button onClick={() => navigate("/dashboard")}>
            Back to Dashboard
          </Button>
        </div>
      </Card>
    </div>
  );
}
