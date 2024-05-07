import { Item } from "./item";

export interface Attribute extends Item {
  name: string;
  type: string;
  value: number | string;
}

export interface NodeStates {
  online: string;
  uptime: string;
}

export interface Node extends Item {
  id: string;
  name: string;
  ip?: string;
  mac?: string;
  online: boolean;
  attributes: Attribute[];
  states: NodeStates;
}
