import { Item } from "./item";

export interface State extends Item {
  entityId: string;
  relId: string;
  name: string;
  state: boolean | string | number;
}
