import { useEffect, useState } from "react";
import { PagedCollection } from "~/types/collection";
import { Attribute, Node } from "~/types/node";
import { PagedResponse } from "~/types/response";
import { fetchApi } from "~/utils/api";
import { useMercure } from "~/utils/mercure";

export interface Props {
  node: Node;
}

export default function Attributes({ node }: Props) {
  const [data, setData] = useState<
    PagedCollection<Attribute> | null | undefined
  >();
  const [error, setError] = useState<string | undefined>();
  const [hubUrl, setHubUrl] = useState<string | null>(null);
  const collection = useMercure(data, hubUrl);

  useEffect(() => {
    (async () => {
      try {
        const response: PagedResponse<Attribute> = await fetchApi(
          `/attributes?subject=${node.id}`
        );

        if (response?.data) {
          setData(response.data);
        }
        if (response && response.hubURL) {
          setHubUrl(response.hubURL);
        }
      } catch (error) {
        console.error(error);
        if (error instanceof Error) {
          setError(error.message);
        }
        return;
      }
    })();
  }, [node]);

  return (
    <>
      {error}
      {collection?.["hydra:member"].map((item) => (
        <div key={item["@id"]}>
          <h1>
            {item.name}: {item.value}
          </h1>
        </div>
      ))}
    </>
  );
}
