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
    public features?: string[],
    _id?: string
  ) {
    this[ "@id" ] = _id;
  }

  public hasFeature(feature: string): boolean {
    return this.features?.includes(feature) ?? false;
  }
}
