package edu.rit.se.a11y.autoresponses.service;

import edu.rit.se.a11y.autoresponses.api.AddSuggestedResponseRequest;
import edu.rit.se.a11y.autoresponses.api.GetSuggestedResponsesRequest;
import edu.rit.se.a11y.autoresponses.api.GetSuggestedResponsesResponse;

public interface SuggestedResponseService {

    GetSuggestedResponsesResponse getSuggestedResponses(GetSuggestedResponsesRequest suggestedRequest);

    void addSuggestedResponse(AddSuggestedResponseRequest addSuggestedResponseRequest);

}
