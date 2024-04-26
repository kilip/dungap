import { DateTime } from "next-auth/providers/kakao";
import { Item } from "./item";

export class Device implements Item {
  public "@id"?: string;

  constructor(
    public ipAddress: string,
    public macAddress?: string,
    public draft: boolean = true,
    public online: boolean = false,
    public uptime?: DateTime,
    public hostname?: string,
    public nickname?: string,
    _id?: string
  ) {
    this[ "@id" ] = _id;
  }
}
