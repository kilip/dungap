export interface Item {
  "@id": string;
  id: string;
}

// eslint-disable-next-line
export const isItem = (data: any): data is Item => "@id" in data;