import { Item } from "./item";

export interface Device extends Item {
  id: string;
  ipAddress: string;
  macAddress: string;
  hostname?: string;
  nickname?: string;
  group?: string;
  online?: boolean;
  draft?: boolean;
  uptime?: number;
}