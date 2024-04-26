import { Item } from "./item";

export class User implements Item {
  public "@id"?: string;

  constructor(
    public name: string,
    public email: string,
    public roles: string,
    _id?: string,
  ) {
    this[ "@id" ] = _id;
  }
}
