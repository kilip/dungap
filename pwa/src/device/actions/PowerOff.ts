"use server";
import { Session } from "next-auth";
import { ActionResponse } from "../../types/actions";
import { config } from "../../util/config";

export default async function powerOff(
  deviceId: string,
  session: Session,
  setActionError?: (error: string) => void
): Promise<ActionResponse> {
  "use server";
  const url = `${config.entrypoint}${deviceId}/power-off`;
  const actionResponse: ActionResponse = {
    error: undefined,
    success: undefined
  };

  const response = await fetch(url, {
    method: "GET",
    headers: {
      Authorization: `Bearer ${session.accessToken}`,
    },
  });

  if (!response.ok) {
    if (401 === response.status) {
      actionResponse.error = "You don't have enough permissions to turn off this device!";
    }
  } else {
    actionResponse.success = "Device turned off successfully!";
  }

  return actionResponse;

}
