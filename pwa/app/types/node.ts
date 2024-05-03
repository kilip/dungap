import { Item } from "./item";

export interface Node extends Item {
  id: string;
  name: string;
  ip?: string;
  mac?: string;
}
