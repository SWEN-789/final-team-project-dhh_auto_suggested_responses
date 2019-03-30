package edu.rit.se.a11y.autoresponses.api;

import java.util.List;

public class GetSuggestedResponsesResponse {

    private List<String> suggestedResponses;

    public GetSuggestedResponsesResponse(List<String> suggestedResponses) {
        this.suggestedResponses = suggestedResponses;
    }

    public List<String> getSuggestedResponses() {
        return suggestedResponses;
    }

}
