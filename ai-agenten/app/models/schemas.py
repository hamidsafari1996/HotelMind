from pydantic import BaseModel, ConfigDict

class UserQuery(BaseModel):
    model_config = ConfigDict(arbitrary_types_allowed=True)
    query: str