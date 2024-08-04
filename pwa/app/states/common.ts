import { useEffect, useState } from "react";
import { FetchResponse } from "~/types/response";
import { State } from "~/types/state";
import { fetchApi } from "~/utils/api";
import { useMercure } from "~/utils/mercure";

export function useLatestState(
  stateName: string,
  hubUrl: string | null
) {
  const [ data, setData ] = useState<State | undefined>(undefined);
  const topic = `/states/latest/${stateName}`;
  const item = useMercure(data, hubUrl, topic);

  useEffect(() => {
    (async () => {
      try {
        const response: FetchResponse<State> | undefined = await fetchApi(topic);
        if (response?.data) {
          setData(response.data);
        } else if ("null" === response?.text) {
          // faking the response
          const empty: State = {
            '@id': topic,
            id: topic,
            entityId: "",
            state: "",
            name: stateName,
          };
          setData(empty);
        }
      } catch (error: unknown) {
        console.log(error);
      }
    })();
  }, [ stateName, topic ]);

  return item;
}
