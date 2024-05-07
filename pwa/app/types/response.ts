import { PagedCollection } from "./collection";

export interface FetchResponse<TData> {
  hubURL: string | null;
  data: TData;
  text: string;
}

export type PagedResponse<TData> = FetchResponse<PagedCollection<TData>> | undefined;
export type ItemResponse<TData> = FetchResponse<TData>;
